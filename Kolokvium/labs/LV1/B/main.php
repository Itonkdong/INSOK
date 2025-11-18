<?php

enum Sector
{
    case TECHNOLOGY;
    case FINANCE;
    case HEALTHCARE;
    case ENERGY;
}

class StockPrice
{

    private string $date;
    private float $closed_price;
    private float $open_price;
    private float $highest_price;
    private float $lowest_price;

    /**
     * @param string $date
     * @param float $closed_price
     * @param float $open_price
     * @param float $highest_price
     * @param float $lowest_price
     */
    public function __construct(string $date, float $closed_price, float $open_price, float $highest_price, float $lowest_price)
    {
        $this->date = $date;
        $this->closed_price = $closed_price;
        $this->open_price = $open_price;
        $this->highest_price = $highest_price;
        $this->lowest_price = $lowest_price;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getClosedPrice(): float
    {
        return $this->closed_price;
    }

}

class Stock
{
    private string $ticker;
    private int $shares_outstanding;
    private Sector $sector;
    /**
     * @var StockPrice[]
     */
    private array $stock_prices;

    /**
     * @param string $ticker
     * @param int $shares_outstanding
     * @param Sector $sector
     */
    public function __construct(string $ticker, int $shares_outstanding, Sector $sector)
    {
        $this->ticker = $ticker;
        $this->shares_outstanding = $shares_outstanding;
        $this->sector = $sector;
        $this->stock_prices = [];
    }

    private function hasStockPrice(StockPrice $stockPrice): bool
    {
        return isset($this->stock_prices[$stockPrice->getDate()]);
    }

    private function hasStockPriceForDate(string $date): bool
    {
        return isset($this->stock_prices[$date]);
    }

    function addStockPrice(StockPrice $stockPrice): void
    {
        if ($this->hasStockPrice($stockPrice))
        {
            echo "There is already a historical price for this date for this stock\n";
            return;
        }

        $this->stock_prices[$stockPrice->getDate()] = $stockPrice;
    }

    function calculateMarketCapForDate(string $date): ?float
    {
        if (!$this->hasStockPriceForDate($date))
        {
            echo "No historical price for this date for this stock\n";
            return null;
        }
        $targetStockPrice = $this->stock_prices[$date];

        return $targetStockPrice->getClosedPrice() * $this->shares_outstanding;
    }

    private function hasAnyStockPrices(): bool
    {
        return count($this->stock_prices) !== 0;
    }

    function getLastClosedPrice(): ?float
    {
        if (!$this->hasAnyStockPrices()) return null;

        $last = array_key_last($this->stock_prices);
        return $this->stock_prices[$last]->getClosedPrice();

    }

    public function getTicker(): string
    {
        return $this->ticker;
    }

    public function __toString(): string
    {
        return "$this->ticker";
    }


}


class StockExchange
{
    private string $exchange_name;
    /**
     * @var array<string, Stock>
     */
    private array $listed_stocks;

    /**
     * @param string $exchange_name
     */
    public function __construct(string $exchange_name)
    {
        $this->exchange_name = $exchange_name;
        $this->listed_stocks = [];
    }

    public function listStock(Stock $stock): void
    {
        $this->listed_stocks[$stock->getTicker()] = $stock;
    }

    private function hasStock(string $ticker): bool
    {
        return isset($this->listed_stocks[$ticker]);
    }

    public function findStockByTicker(string $ticker): ?Stock
    {
        if (!$this->hasStock($ticker))
        {
            echo "Stock not found\n";
            return null;
        }

        return $this->listed_stocks[$ticker];
    }

    public function __toString(): string
    {
        return "$this->exchange_name";
    }


}

class Portfolio
{
    public int $cash;
    public array $stock_holdings;

    /**
     * @param int $cash
     */
    public function __construct(int $cash)
    {
        $this->cash = $cash;
        $this->stock_holdings = [];
    }

    private function ownsStock($ticker): bool
    {
        return isset($this->stock_holdings[$ticker]);
    }

    private function getStockData(string $ticker):array
    {
        return $this->stock_holdings[$ticker];
    }

    private function buyNewStock(Stock $stock, int $numberOfShares): void
    {
        $this->stock_holdings[$stock->getTicker()] = ["numberOfShares" => $numberOfShares, "stock" => $stock];
    }

    private function increaseAlreadyExistingStock(string $ticker, int $numberOfShares): void
    {
        $data = $this->getStockData($ticker);
        $data["numberOfShares"] += $numberOfShares;
        $this->stock_holdings[$ticker] = $data;
    }

    public function buyStock(string $ticker, int $numberOfShares, StockExchange $stockExchange): void
    {
        $stock = $stockExchange->findStockByTicker($ticker);

        if (!isset($stock))
        {
            echo "Stock not found\n";
            return;
        }

        $lastClosedPrice = $stock->getLastClosedPrice();

        if (!isset($lastClosedPrice))
        {
            echo "No price available for this stock\n";
            return;

        }

        $total = $lastClosedPrice * $numberOfShares;

        if ($total > $this->cash)
        {
            echo "Insufficient cash to buy this stock\n";
            return;
        }

        if ($this->ownsStock($ticker))
        {
            $this->increaseAlreadyExistingStock($ticker, $numberOfShares);
        }
        else
        {
            $this->buyNewStock($stock, $numberOfShares);
        }

        echo "Bought $numberOfShares shares of $stock for $total USD on $stockExchange\n";

        $this->cash -= $total;
    }



    private function sellAllStocks(string $ticker):void
    {
        unset($this->stock_holdings[$ticker]);
        $this->stock_holdings = array_values($this->stock_holdings);
    }
    private function sellPartiallyOwningStocks(string $ticker, int $numberOfShares):void
    {
        $data = $this->getStockData($ticker);
        $data["numberOfShares"] -= $numberOfShares;
        $this->stock_holdings[$ticker] = $data;
    }


    function sellStock(string $ticker, int $numberOfShares, StockExchange $stockExchange): void
    {

        $stock = $stockExchange->findStockByTicker($ticker);

        if (!isset($stock))
        {
            echo "Stock not found\n";
            return;
        }

        $data = $this->getStockData($ticker);

        if ($data["numberOfShares"] < $numberOfShares)
        {
            echo "Not enough shares to sell\n";
            return;
        }

        $lastClosedPrice = $stock->getLastClosedPrice();

        if (!isset($lastClosedPrice))
        {
            echo "No price available for this stock\n";
            return;

        }

        $total = $lastClosedPrice * $numberOfShares;

        if ($data["numberOfShares"] == $numberOfShares)
        {
            $this->sellAllStocks($ticker);
        }
        else
        {
           $this->sellPartiallyOwningStocks($ticker,$numberOfShares);
        }

        $this->cash += $total;

        echo "Sold $numberOfShares shares of $stock for $total USD on $stockExchange\n";

    }


}


function main(): void
{
    $applePrice1 = new StockPrice('01/01/2025', 100.0, 95.0, 102.0, 90.0);
    $applePrice2 = new StockPrice('02/01/2025', 110.0, 100.0, 115.0, 98.0);
    $applePrice3 = new StockPrice('03/01/2025', 120.0, 112.0, 125.0, 110.0);

    $appleStock = new Stock('AAPL', 16000000000.0, Sector::TECHNOLOGY);
    $appleStock->addStockPrice($applePrice1);
    $appleStock->addStockPrice($applePrice2);
    $appleStock->addStockPrice($applePrice3);

    $microsoftStock = new Stock('MSFT', 7500000000.0, Sector::TECHNOLOGY);
    $microsoftStock->addStockPrice(new StockPrice('01/01/2025', 300.0, 295.0, 310.0, 290.0));

    $nasdaq = new StockExchange('NASDAQ');
    $nasdaq->listStock($appleStock);
    $nasdaq->listStock($microsoftStock);

    echo "MarketCap 02/01/2025: " . ($appleStock->calculateMarketCapForDate('02/01/2025') ?? 'null') . " USD\n";

    $portfolio = new Portfolio(10000.0);
    $portfolio->buyStock('AAPL', 10, $nasdaq);
    $portfolio->buyStock('MSFT', 5, $nasdaq);
    $portfolio->buyStock('MSFT', 1000, $nasdaq);
    $portfolio->sellStock('AAPL', 4, $nasdaq);
    $portfolio->sellStock('MSFT', 50, $nasdaq);

    echo "Cash: {$portfolio->cash} USD\n";
    print_r($portfolio->stock_holdings);

}

main();