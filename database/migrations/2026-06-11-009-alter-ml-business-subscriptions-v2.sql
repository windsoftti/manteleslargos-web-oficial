ALTER TABLE ml_business_subscriptions

ADD COLUMN id_account INT NULL AFTER id_subscription,

ADD COLUMN trial_starts_at DATETIME NULL AFTER grace_until,
ADD COLUMN trial_expires_at DATETIME NULL AFTER trial_starts_at,

ADD COLUMN prorated_amount DECIMAL(10,2) NULL AFTER trial_expires_at;