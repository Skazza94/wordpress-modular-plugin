<?php
/**
 * Apply a filter on cart items to disable "Checkout" button if conditions aren't met.
 *
 * @author Skazza
 */
class WooCommerceCartFilter extends WooCommerceFilter {
    /**
     * When filter conditions aren't met, disable the "Checkout" button in cart page.
     *
     * @param array $currentValues Values that haven't reached the threshold.
     * @author Skazza
     */
    protected function notifyFilterError($currentValues) {
        remove_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20);

        wc_print_notice(
            sprintf(
                __('<p><strong>A Minimum of %s is required before checking out.</strong></p><p>Current cart\'s total: %s.</p>', PLUGIN_NAME),
                wc_price($this->THRESHOLD),
                wc_price($currentValues['total'])
            ),
            'error'
        );
    }
}