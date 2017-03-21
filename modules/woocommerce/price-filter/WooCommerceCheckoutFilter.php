<?php
/**
 * Apply a filter in the checkout page to disable "Place Order" button if conditions aren't met.
 *
 * @author Skazza
 */
class WooCommerceCheckoutFilter extends WooCommerceFilter {
    /**
     * When filter conditions aren't met, disable the "Place Order" button in checkout page.
     *
     * @param array $currentValues Values that haven't reached the threshold.
     * @author Skazza
     */
    protected function notifyFilterError($currentValues) {
        wc_add_notice(
            sprintf(
                __('<p><strong>A Minimum of %s is required before checking out.</strong></p><p>Current cart\'s total: %s.</p>', PLUGIN_NAME),
                wc_price($this->THRESHOLD),
                wc_price($currentValues['total'])
            ),
            'error'
        );
    }
}