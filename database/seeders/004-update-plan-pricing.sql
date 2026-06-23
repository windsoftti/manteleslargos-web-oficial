UPDATE ml_planes
SET
    monthly_price = 0,
    semiannual_price = 0,
    annual_price = 0
WHERE slug = 'basico';

UPDATE ml_planes
SET
    monthly_price = 299,
    semiannual_price = 1499,
    annual_price = 2799
WHERE slug = 'premium';

UPDATE ml_planes
SET
    monthly_price = 499,
    semiannual_price = 2499,
    annual_price = 4699
WHERE slug = 'pro';

UPDATE ml_planes
SET
    monthly_price = 999,
    semiannual_price = 4999,
    annual_price = 9499
WHERE slug = 'enterprise';