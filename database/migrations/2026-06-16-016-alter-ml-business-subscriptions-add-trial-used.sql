ALTER TABLE ml_business_subscriptions
ADD COLUMN trial_used ENUM('yes','no')
NOT NULL
DEFAULT 'no'
AFTER trial_expires_at;