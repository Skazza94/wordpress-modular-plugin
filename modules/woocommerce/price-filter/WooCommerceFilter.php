<?php
/**
 * Defines a filter to apply and when conditions aren't met, the notifyFilterError declared in subclasses
 * handles the error.
 *
 * @author Skazza
 */
abstract class WooCommerceFilter {
    /**
     * @var int Condition for the filter.
     */
    protected $THRESHOLD = 50;

    /**
     * Apply the filter rules for cart items, this function is hooked in WordPress core.
     *
     * @author Skazza
     */
    public function applyFilter() {
        /* Only run in the Checkout and Cart pages. */
        if(is_cart() || is_checkout()) {
            /* Total before taxes are applied. */
            $total = WC()->cart->subtotal;
            //Oppure $total = WC()->cart->total con le tasse

            /* Compare current value with threshold */
            if($total < $this->THRESHOLD)
                $this->notifyFilterError(array('total' => $total)); /* If the filter conditions are not met, notify the error */
        }
    }

    /**
     * When filter conditions aren't met, this method is invoked to do something with the error.
     *
     * @param array $currentValues Values that haven't reached the threshold.
     * @author Skazza
     */
    protected abstract function notifyFilterError($currentValues);
}