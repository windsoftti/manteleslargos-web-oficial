ALTER TABLE ml_business_subscriptions

MODIFY COLUMN status ENUM(
    'pending',
    'trial',
    'active',
    'grace',
    'expired',
    'cancelled'
)
NOT NULL DEFAULT 'pending';