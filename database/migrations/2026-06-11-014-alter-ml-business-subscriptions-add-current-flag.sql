ALTER TABLE ml_business_subscriptions

ADD COLUMN is_current ENUM(
    'yes',
    'no'
)
NOT NULL DEFAULT 'yes'
AFTER status;