<?php

namespace LAShowroom\TaxJarBundle\Model;

final class TaxCategory
{
    /**
     * All human wearing apparel suitable for general use.
     */
    const CLOTHING = 20010;

    /**
     * Pre-written software, delivered electronically, but access remotely.
     */
    const SAAS = 30070;

    /**
     * Digital products transferred electronically, meaning obtained by the purchaser by means other than tangible storage media.
     */
    const DIGITAL_GOODS = 31000;

    /**
     * Food for humans consumption, unprepared.
     */
    const FOOD_GROCERIES = 40030;

    /**
     * Drugs for human use without a prescription.
     */
    const DRUGS_NONPRESCRIPTION = 51010;

    /**
     * Drugs for human use with a prescription.
     */
    const DRUGSS_PRESCRIPTION = 51020;

    /**
     * Books, printed.
     */
    const BOOKS_GENERAL = 81100;

    /**
     * Textbooks, printed.
     */
    const BOOKS_TEXTBOOKS = 81110;

    /**
     * Religious books and manuals, printed.
     */
    const BOOKS_RELIGIOUS = 81120;

    /**
     * Periodicals, printed, sold by subscription.
     */
    const MAGAZINES_SUBSCRIPTIONS = 81300;

    /**
     * Periodicals, printed, sold individually.
     */
    const MAGAZINE = 81310;

    /**
     * Exempt Items
     */
    const EXEMPT_OTHER = 99999;
}
