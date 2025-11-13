<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('has_feature_access'))
{
    /**
     * Check if a user has access to a specific feature based on their subscription plan.
     *
     * @param object $user_data The user object containing 'subscription_plan'.
     * @param string $feature_name The name of the feature to check.
     * @return bool
     */
    function has_feature_access($user_data, $feature_name)
    {
        if (empty($user_data) || !isset($user_data->subscription_plan)) {
            return false; // User data or plan not available
        }

        $plan = $user_data->subscription_plan;

        // Define feature access rules
        $feature_access_map = array(
            'metadata_storage_limit' => array(
                'free' => 10000, // Example limit
                'pro' => 100000,
                'enterprise' => -1 // Unlimited
            ),
            'folder_limit' => array(
                'free' => 50,
                'pro' => 500,
                'enterprise' => -1
            ),
            'smart_collection_limit' => array(
                'free' => 5,
                'pro' => 50,
                'enterprise' => -1
            ),
            'advanced_notifications' => array(
                'free' => false,
                'pro' => true,
                'enterprise' => true
            ),
            'leaderboard_visibility' => array(
                'free' => false,
                'pro' => true,
                'enterprise' => true
            ),
            'create_public_collections' => array(
                'free' => false,
                'pro' => true,
                'enterprise' => true
            ),
            'team_management' => array(
                'free' => false,
                'pro' => false,
                'enterprise' => true
            ),
            // Add more features and their access levels here
        );

        if (isset($feature_access_map[$feature_name])) {
            return $feature_access_map[$feature_name][$plan];
        }

        // Default to false if feature not defined or not accessible by default
        return false;
    }

    /**
     * Get a specific limit value for a user's plan.
     *
     * @param object $user_data The user object containing 'subscription_plan'.
     * @param string $limit_type The type of limit to check (e.g., 'metadata_storage_limit').
     * @return int|null The limit value, or null if not defined.
     */
    function get_user_plan_limit($user_data, $limit_type)
    {
        if (empty($user_data) || !isset($user_data->subscription_plan)) {
            return null;
        }

        $plan = $user_data->subscription_plan;

        $limits_map = array(
            'metadata_storage_limit' => array(
                'free' => 10000,
                'pro' => 100000,
                'enterprise' => -1
            ),
            'folder_limit' => array(
                'free' => 50,
                'pro' => 500,
                'enterprise' => -1
            ),
            'smart_collection_limit' => array(
                'free' => 5,
                'pro' => 50,
                'enterprise' => -1
            ),
            'notification_rules_limit' => array(
                'free' => 3,
                'pro' => 50,
                'enterprise' => -1
            ),
            // Add more limits here
        );

        if (isset($limits_map[$limit_type])) {
            return $limits_map[$limit_type][$plan];
        }

        return null;
    }
}
