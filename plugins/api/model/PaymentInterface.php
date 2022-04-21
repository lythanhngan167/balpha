<?php


namespace api\model;


interface PaymentInterface
{
    function setParams();
    function purchase();
    function validatePayment();
    function getError();
}