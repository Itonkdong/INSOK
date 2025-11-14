<?php


enum Sector: string
{
    case TECHNOLOGY = "TECHNOLOGY";
    case FINANCE = "FINANCE";
    case HEALTHCARE = "HEALTHCARE";
    case ENERGY = "ENERGY";
}

class StockPrice
{
    private string $date;
    private float $closed_price;
    private float $opened_price;
    private float $highest_price;
    private float $lowest_price;/**
 *
 *
 * @param DateTime $date
 * @param float $closed_price
 * @param float $opened_price
 * @param float $highest_price
 * @param float $lowest_price
 */
    public function __construct(string $date, float $closed_price, float $opened_price, float $highest_price, float $lowest_price)
    {
        $this->date = $date;
        $this->closed_price = $closed_price;
        $this->opened_price = $opened_price;
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

    public function getOpenedPrice(): float
    {
        return $this->opened_price;
    }

    public function getHighestPrice(): float
    {
        return $this->highest_price;
    }

    public function getLowestPrice(): float
    {
        return $this->lowest_price;
    }



}

class Stock
{

    private string $ticker;
    private int $shares_outstanding;
    private Sector $sector;
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


    function addStockPrice(StockPrice $stockPrice):void
    {

        if (!isset($this->stock_prices[$stockPrice->getDate()]))
        {
            $this->stock_prices[$stockPrice->getDate()] = $stockPrice;
        }
        else
        {
            echo "There is already a historical price for this date for this stock\n";
        }

    }

    function calculateMarketCapForDate(string $date): ?float
    {

        if(!isset($this->stock_prices[$date]))
        {
            echo "No historical price for this date for this stock\n";
            return null;
        }

        /** @var StockPrice $stockPrice */
        $stockPrice = $this->stock_prices[$date];

        return $this->shares_outstanding * $stockPrice->getClosedPrice();

    }

    function getLastClosedPrice():?float
    {
        if(count($this->stock_prices) === 0)
        {
            return null;
        }

        /** @var StockPrice $lastStockPrice */
        $lastStockPrice = $this->stock_prices[array_key_last($this->stock_prices)];


        return $lastStockPrice->getClosedPrice();
    }

    public function getTicker(): string
    {
        return $this->ticker;
    }

    public function getSector(): Sector
    {
        return $this->sector;
    }




}

class StockExchange
{
    private string $exchange_name;

    /** @var array Stock  */
    private array $listed_stocks;

    /**
     * @param string $exchange_name
     */
    public function __construct(string $exchange_name)
    {
        $this->exchange_name = $exchange_name;
        $this->listed_stocks = [];
    }

    function listStock(Stock $stock) :void
    {
        $this->listed_stocks[] = $stock;
    }

    function findStockByTicker(string $ticker): ?Stock
    {
        $filtered = array_filter($this->listed_stocks, fn(Stock $stock) => $stock->getTicker() == $ticker);

        if (count($filtered) === 0)
        {
            echo "Stock not found\n";
            return null;
        }

        return array_pop($filtered);
    }

    public function getExchangeName(): string
    {
        return $this->exchange_name;
    }


}

class Portfolio
{
    public float $cash;
    public array $stockHoldings;

    /**
     * @param float $cash
     */
    public function __construct(float $cash)
    {
        $this->cash = $cash;
        $this->stockHoldings = [];
    }

    function buyStock(string $ticker, int $numberOfShares, StockExchange $stockExchange):void
    {
        /** @var Stock $stock */
        $stock = $stockExchange->findStockByTicker($ticker);

        if ($stock === null)
        {
            echo "Stock not found\n";
            return;
        }

        $lastClosedPrice = $stock->getLastClosedPrice();

        if ($lastClosedPrice === null)
        {
            echo "No price available for this stock\n";
        }

        $total = $lastClosedPrice * $numberOfShares;

        if ($total > $this->cash)
        {
            echo "Insufficient cash to buy this stock\n";
            return;
        }

        foreach ($this->stockHoldings as $holding)
        {
            /** @var Stock $stockHolding */
            $stockHolding = $holding["stock"];
            if ($stockHolding->getTicker() === $ticker)
            {
                $holding["numberOfShares"] += $numberOfShares;
                $this->cash -= $total;
                echo "Bought $numberOfShares shares of {$stock->getTicker()} for {$total} USD on {$stockExchange->getExchangeName()}";
                return;
            }
        }


        $this->stockHoldings[] = ['numberOfShares' => $numberOfShares, 'stock' => $stock];
        $this->cash -= $total;

        echo "Bought $numberOfShares shares of {$stock->getTicker()} for {$total} USD on {$stockExchange->getExchangeName()}";

    }

    function sellStock(string $ticker,  int $numberOfShares, StockExchange $stockExchange): void
    {
        /** @var Stock $stock */
        $stock = $stockExchange->findStockByTicker($ticker);

        if ($stock === null)
        {
            echo "Stock not found";
            return;
        }

        foreach ($this->stockHoldings as $index=>$holding)
        {
            /** @var Stock $stockHaving */
            $stockHaving = $holding["stock"];

            if ($stockHaving->getTicker() === $ticker)
            {
                $numberOfSharesHaving = $holding["numberOfShares"];
                if ($numberOfSharesHaving < $numberOfShares)
                {
                    echo "Not enough shares to sell\n";
                    return;
                }

                $lastClosedPrice = $stockHaving->getLastClosedPrice();
                if ($lastClosedPrice === null)
                {
                    echo "No price available for this stock\n";
                    return;
                }

                $total = $lastClosedPrice * $numberOfShares;
                $this->cash += $total;

                if ($numberOfSharesHaving == $numberOfShares)
                {
                    unset($holding, $this->stockHoldings);
                }
                else
                {
                    $holding["numberOfShares"] -= $numberOfShares;
                    $this->stockHoldings[$index] = $holding;
                }

                echo "Sold {$numberOfShares} shares of {$stockHaving->getTicker()} for {$total} USD on {$stockExchange->getExchangeName()}\n";
                return;
            }
        }
    }
}


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

echo "MarketCap 02/01/2025: ".($appleStock->calculateMarketCapForDate('02/01/2025') ?? 'null')." USD\n";

$portfolio = new Portfolio(10000.0);
$portfolio->buyStock('AAPL', 10, $nasdaq);
$portfolio->buyStock('MSFT', 5, $nasdaq);
$portfolio->buyStock('MSFT', 1000, $nasdaq);
$portfolio->sellStock('AAPL', 4, $nasdaq);
$portfolio->sellStock('MSFT', 50, $nasdaq);

echo "Cash: {$portfolio->cash} USD\n";
print_r($portfolio->stockHoldings);





