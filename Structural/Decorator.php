<?php

/*
 * Component Interface
 */
interface Invoice
{
    public function productsDescription(): string;
    public function invoiceDate(): DateTime;
    public function totalPrice(): int;
}

/*
 * Concrete component - Electronic product invoice
 */
class EPInvoice implements Invoice
{
    protected array $products;

    public function setProduct(Product $product)
    {
        $this->products[] = $product;
    }

    public function productsDescription(): string
    {
        $text = 'Electronic invoice for ';

        foreach($this->products as $key => $product)
        {
            $text .= ($key > 0 ? ' - ' : '') . $product->getTitle();
        }

        return $text;
    }

    public function invoiceDate(): DateTime
    {
        return new DateTime();
    }

    public function totalPrice(): int
    {
        $price = 0;

        foreach($this->products as $product)
        {
            $price += $product->getPrice();
        }

        return $price;
    }
}

/*
 * Product class
 */
class Product
{
    protected string $title;
    protected int $price;

    public function __construct(string $title, int $price)
    {
        $this->title = $title;
        $this->price = $price;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPrice(): int
    {
        return $this->price;
    }
}

/*
 * Decorator abstract class
 */
abstract class InvoiceDecorator implements Invoice
{
    protected Invoice $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function invoiceDate(): DateTime
    {
        return $this->invoice->invoiceDate();
    }
}

/*
 * Concrete decorator pattern
 */
class ProductWithOffer extends InvoiceDecorator
{
    protected int $offerCount;

    public function __construct(Invoice $invoice, int $offerCount)
    {
        parent::__construct($invoice);

        $this->offerCount = $offerCount;
    }

    public function productsDescription(): string
    {
        return $this->invoice->productsDescription() . ' with %' . $this->offerCount . ' off';
    }

    public function totalPrice(): int
    {
        return $this->invoice->totalPrice() * ((100 - $this->offerCount)/100);
    }
}

class ProductWithTax extends InvoiceDecorator
{
    protected int $tax;

    public function __construct(Invoice $invoice, int $tax)
    {
        parent::__construct($invoice);

        $this->tax = $tax;
    }

    public function productsDescription(): string
    {
        return $this->invoice->productsDescription() . ' with $' . $this->tax . ' for tax';
    }

    public function totalPrice(): int
    {
        return $this->invoice->totalPrice() + $this->tax;
    }
}

/*
 * Client:
 */
$invoice = new EPInvoice();
$invoice->setProduct(new Product('Python Course', 250));
$invoice->setProduct(new Product('PHP Course', 200));
$invoice->setProduct(new Product('Javascript Course', 300));

echo 'Description: ' . $invoice->productsDescription()                     . '<br />';
echo 'Date: '        . $invoice->invoiceDate()->format('Y-m-d h:i') . '<br />';
echo 'Total price: ' . $invoice->totalPrice() . '$'                        . '<br />';

$offer = new ProductWithOffer($invoice, 25);

echo 'Description: ' . $offer->productsDescription()                       . '<br />';
echo 'Date: '        . $invoice->invoiceDate()->format('Y-m-d h:i') . '<br />';
echo 'Total price: ' . $offer->totalPrice() . '$'                          . '<br />';

$tax = new ProductWithTax($offer, 50);

echo 'Description: ' . $tax->productsDescription()                         . '<br />';
echo 'Date: '        . $invoice->invoiceDate()->format('Y-m-d h:i') . '<br />';
echo 'Total price: ' . $tax->totalPrice() . '$'                            . '<br />';

/*
 * Output:
 *
 * Description: Electronic invoice for Python Course - PHP Course - Javascript Course
 * Date: 2022-05-19 09:25
 * Total price: 750$
 *
 * Description: Electronic invoice for Python Course - PHP Course - Javascript Course with %25 off
 * Date: 2022-05-19 09:25
 * Total price: 562$
 *
 * Description: Electronic invoice for Python Course - PHP Course - Javascript Course with %25 off with $50 for tax
 * Date: 2022-05-19 09:25
 * Total price: 612$
 */
