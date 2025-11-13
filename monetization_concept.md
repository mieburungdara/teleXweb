# Monetization Concept (MySQL)

To ensure the long-term sustainability and growth of the TeleXweb project, a tiered subscription-based monetization model will be implemented. This model will offer a free tier with basic functionalities and premium tiers that unlock advanced features, higher limits, and exclusive benefits.

## Proposed Tiers:

1.  **Free Tier:**
    *   **Metadata Storage:** Limited capacity (e.g., 10,000 file metadata entries).
    *   **Folders:** Limited number (e.g., 50 folders).
    *   **Smart Collections:** Limited number (e.g., 5 smart collections).
    *   **Notification Rules:** Limited active rules (e.g., 3 active rules), basic `file_tag_match` trigger type only.
    *   **Gamification:** Basic achievements, XP tracking, but no leaderboard visibility.
    *   **Public Collections:** Can view, but not create/curate.
    *   **Access Logs:** Limited history (e.g., last 7 days).
    *   **Support:** Community support only.

2.  **TeleX Pro Tier:**
    *   **Metadata Storage:** Increased capacity (e.g., 100,000 file metadata entries) or unlimited.
    *   **Folders:** Increased limit or unlimited.
    *   **Smart Collections:** Increased limit or unlimited.
    *   **Notification Rules:** Increased active rules, access to all `trigger_type`s (comments, achievements, system announcements), more advanced throttling options.
    *   **Gamification:** Full leaderboard visibility, exclusive achievements.
    *   **Public Collections:** Ability to create and curate.
    *   **Access Logs:** Extended history (e.g., 90 days) or unlimited.
    *   **Advanced Analytics:** Access to more detailed user analytics.
    *   **Support:** Priority email support.

3.  **TeleX Enterprise Tier:**
    *   All TeleX Pro features.
    *   **Team Management:** Multiple users under one account with role-based access.
    *   **Enhanced Audit Trails:** More granular logging and longer retention.
    *   **Custom Branding:** For shared folders and public collections.
    *   **Custom Integrations:** API access for custom integrations.
    *   **Support:** Dedicated account manager, SLA-backed support.

## Database Changes:

### Table: `users` (Additions)

| Column Name           | Data Type         | Description                                                  | Notes                                      |
|-----------------------|-------------------|--------------------------------------------------------------|--------------------------------------------|
| `subscription_plan`   | `ENUM('free','pro','enterprise')` | The user's current subscription plan.                        | NOT NULL, DEFAULT 'free'.                  |
| `subscription_start_date` | `DATETIME`    | Date when the current subscription plan started.             | Nullable.                                  |
| `subscription_end_date` | `DATETIME`    | Date when the current subscription plan ends.                | Nullable. For recurring subscriptions, this might be the next billing date. |
| `payment_status`      | `VARCHAR(50)`     | Status of the user's payment (e.g., 'active', 'canceled', 'past_due'). | Nullable.                                  |

### Table: `subscriptions` (New Table)

This table will manage subscription details and payment history.

| Column Name           | Data Type         | Description                                                  | Notes                                      |
|-----------------------|-------------------|--------------------------------------------------------------|--------------------------------------------|
| `id`                  | `BIGINT UNSIGNED` | Primary key for the subscription record.                     | Auto-incrementing.                         |
| `user_id`             | `BIGINT UNSIGNED` | ID of the user who owns this subscription.                   | Foreign key to `users.id`. NOT NULL. Indexed. |
| `plan_name`           | `VARCHAR(50)`     | Name of the subscribed plan (e.g., 'pro', 'enterprise').     | NOT NULL.                                  |
| `stripe_customer_id`  | `VARCHAR(255)`    | Customer ID from the payment gateway (e.g., Stripe).         | Nullable.                                  |
| `stripe_subscription_id` | `VARCHAR(255)` | Subscription ID from the payment gateway.                    | Nullable.                                  |
| `amount`              | `DECIMAL(10,2)`   | Amount paid for the subscription.                            | NOT NULL.                                  |
| `currency`            | `VARCHAR(3)`      | Currency of the payment (e.g., 'USD', 'IDR').                | NOT NULL.                                  |
| `interval`            | `VARCHAR(20)`     | Billing interval (e.g., 'month', 'year').                    | Nullable.                                  |
| `status`              | `VARCHAR(50)`     | Current status of the subscription (e.g., 'active', 'canceled', 'trialing', 'past_due'). | NOT NULL.                                  |
| `trial_ends_at`       | `DATETIME`        | Timestamp when the trial period ends.                        | Nullable.                                  |
| `current_period_start` | `DATETIME`       | Start of the current billing period.                         | Nullable.                                  |
| `current_period_end`  | `DATETIME`        | End of the current billing period.                           | Nullable.                                  |
| `canceled_at`         | `DATETIME`        | Timestamp when the subscription was canceled.                | Nullable.                                  |
| `created_at`          | `DATETIME`        | Timestamp when the subscription record was created.          | Defaults to `CURRENT_TIMESTAMP`.           |
| `updated_at`          | `DATETIME`        | Timestamp when the record was last updated.                  | Defaults to `CURRENT_TIMESTAMP` ON UPDATE CURRENT_TIMESTAMP. |

### Relationships

*   `subscriptions.user_id` references `users.id`.

## Implementation Suggestions:

1.  **Integrate Payment Gateway:** Choose a payment provider (e.g., Stripe, PayPal) and integrate their APIs for subscription management (checkout, webhooks for status updates, refunds).
2.  **Feature Gating Logic:** Implement checks throughout the application (controllers, models, views) to restrict access to premium features based on the user's `subscription_plan` and `payment_status`.
3.  **Admin Panel for Subscription Management:** Add functionality to the `Admin.php` controller to view, manage, and modify user subscriptions.
4.  **Upgrade/Downgrade UI:** Develop a user-facing UI for managing subscriptions, including upgrading, downgrading, and viewing billing history.
5.  **Trial Periods:** Implement logic for free trial periods for premium features.
6.  **Clear Communication:** Ensure clear communication to users about what features are available in each tier and the benefits of upgrading.
7.  **Legal & Compliance:** Address terms of service, privacy policy, and payment processing regulations.
8.  **Marketing & Pricing Strategy:** Develop a clear pricing strategy and marketing materials for the different tiers.
