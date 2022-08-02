<?php
namespace App\Libraries;

class Midtranspayment
{
    private $addition = 0;
    private $deduction = 0;
    private $ordernumber = null;
    private $payment_methods = array();
    private $customer_details = array();
    private $transaction = array();
    private $item_details = array();
    private $billing_address = array();
    private $shipping_address = array();

    // public function init(){

    // }
    /**
     * @param array payment_methods
     * @return object
     */
    public function set_payment_method($payment_methods)
    {
        $this->payment_methods = $payment_methods;
        return $this;
    }

    /**
     * @param string id
     * @param string price
     * @param string quantity
     * @param string name
     * @return object
     */
    public function set_item_details($id, $price, $quantity, $name)
    {
        $this->item_details[] = array(
            'id' => $id,
            'price' => $price,
            'quantity' => $quantity,
            'name' => $name
        );
        return $this;
    }


    /**
     * @param int addition
     */
    public function set_addition($addition)
    {
        $this->addition = $addition;
        $this->item_details[] = array(
            'id' => rand(),
            'price' => $addition,
            'quantity' => 1,
            'name' => "addition"
        );
        return $this;
    }

    /**
     * @param int addition
     */
    public function set_deduction($deduction)
    {
        $this->deduction = $deduction;
        $this->item_details[] = array(
            'id' => rand(),
            'price' => -1 * $deduction,
            'quantity' => 1,
            'name' => "deduction"
        );
        return $this;
    }


    /**
     * @param string first_name
     * @param string last_name
     * @param string address
     * @param string city
     * @param string phone
     * @param string country_code
     * @return object
     */
    public function set_billing_address($first_name,$address, $phone, $country_code)
    {
        $this->billing_address = array(
            'first_name'    => $first_name,
            'address'       => $address,
            'phone'         => $phone,
            'country_code'  => $country_code
        );
        return $this;
    }

    /**
     * @param string first_name
     * @param string last_name
     * @param string address
     * @param string city
     * @param string phone
     * @param string country_code
     * @return object
     */
    public function set_shipping_address($first_name, $last_name, $address, $city, $postal_code, $phone, $country_code)
    {
        $this->shipping_address = array(
            'first_name'    => $first_name,
            'last_name'     => $last_name,
            'address'       => $address,
            'city'          => $city,
            'postal_code'   => $postal_code,
            'phone'         => $phone,
            'country_code'  => $country_code
        );
        return $this;
    }

    /**
     * @param string 
     */
    public function set_ordernumber($ordernumber)
    {
        $this->ordernumber = $ordernumber;
        return $this;
    }

    /**
     * @return array 
     */
    private function get_transaction_details()
    {
        $transaction_details = array(
            'order_id' => $this->ordernumber,
            'gross_amount' => 0, // no decimal allowed for creditcard
        );

        foreach ($this->item_details as $item) {
            $transaction_details['gross_amount'] += $item['price'] * $item['quantity'];
        }

        $transaction_details['gross_amount'] += $this->addition;
        $transaction_details['gross_amount'] -= $this->deduction;

        return $transaction_details;
    }

    /**
     * @param string first_name
     * @param string last_name
     * @param string email
     * @param string phone
     * @return object
     */
    public function set_customer($first_name,$email, $phone)
    {
        $this->customer_details = array(
            'first_name'    => $first_name,
            'email'         => $email,
            'phone'         => $phone,
            'billing_address'  => $this->billing_address,
            'shipping_address' =>  $this->shipping_address
        );

        return $this;
    }

    /**
     * @return string token
     */
    public function get_token($enable_payments,$transaction_details,$customer_details,$item_details)
    {
        $this->setConfiguration();
        $transaction = array(
            'enabled_payments' => $enable_payments,
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
        );
        // return var_dump($params);
        return \Midtrans\Snap::getSnapToken($transaction);
    }

    private function setConfiguration()
    {
        $isproduction = true;
        $key = !$isproduction ? 'SB-Mid-server-yujXXerH903-bUws2JF-Cl0K' : 'Mid-server-TRyg-peJd8lS-i76d8tuicFD';
        \Midtrans\Config::$serverKey = $key;
        \Midtrans\Config::$isProduction = $isproduction;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
        return $this;
    }

    public function notification()
    {
        $this->setConfiguration();
        $notif = new \Midtrans\Notification();
        return $notif;
    }
}
