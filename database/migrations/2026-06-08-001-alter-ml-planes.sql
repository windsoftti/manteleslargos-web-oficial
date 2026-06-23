/*
|--------------------------------------------------------------------------
| ALTER ml_planes
|--------------------------------------------------------------------------
| Agrega descripción y status
*/

ALTER TABLE ml_planes
ADD COLUMN description TEXT NULL AFTER Plan,
ADD COLUMN status ENUM(
    'active',
    'inactive'
) NOT NULL DEFAULT 'active' AFTER slug;