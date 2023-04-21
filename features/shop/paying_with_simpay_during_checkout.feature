@paying_with_simpay_for_order
Feature: Paying with SimPay during checkout
    In order to buy products
    As a Customer
    I want to be able to pay with SimPay

    Background:
        Given the store operates on a single channel in "United States" but with "PLN" currency
        And there is a user "dominik@example.com" identified by "password123"
        And the store has a payment method "SimPay" with a code "simpay" and SimPay Checkout Gateway
        And the store has a product "PHP T-Shirt" priced at "$50.23"
        And the store ships everywhere for free
        And I am logged in as "dominik@example.com"

    @ui
    Scenario: Successful payment
        Given I added product "PHP T-Shirt" to the cart
        And I have proceeded selecting "SimPay" payment method
        When I confirm my order with SimPay payment
        And I sign in to SimPay and pay successfully
        Then I should be notified that my payment has been completed

    @ui
    Scenario: Cancelling the payment
        Given I added product "PHP T-Shirt" to the cart
        And I have proceeded selecting "SimPay" payment method
        When I confirm my order with SimPay payment
        And I cancel my SimPay payment
        Then I should be notified that my payment has been cancelled
        And I should be able to pay again
