<?php

function renderSidebarLink(array $item): string
{
    $module = $item['module'] ?? null;

    $action = $item['action'] ?? 'view';

    $hasPermission = true;

    // VALIDAR PERMISO
    if ($module) {

        $hasPermission = userCan(
            $module,
            $action
        );
    }

    // LINK
    $href = $hasPermission
        ? $item['url']
        : '#';

    // MODAL PREMIUM
    $onclick = !$hasPermission
        ? 'onclick="showPremiumModal(event)"'
        : '';

    // CLASES
    $linkClass = !$hasPermission
        ? 'sidebar-link sidebar-link-premium'
        : 'sidebar-link';

    // BADGE
    $badge = !$hasPermission
        ? '<span class="badge badge-warning badge-pill ml-auto">PRO</span>'
        : '';

    // CONTADOR
    $counter = '';

    if (!empty($item['counter'])) {

        $counter = '
            <span class="sidebar-item-number ml-auto text-primary fs-15 font-weight-bold">
                ' . $item['counter'] . '
            </span>
        ';
    }

    // PRIORIDAD BADGE
    $rightContent = $badge ?: $counter;

    return '
        <li class="list-group-item px-3 px-xl-4 py-2 sidebar-item">

            <a
                href="' . $href . '"

                class="text-heading lh-1 ' . $linkClass . ' d-flex align-items-center"

                ' . $onclick . '
            >

                <span class="sidebar-item-icon d-inline-block mr-3 fs-20">

                    <i class="' . $item['icon'] . '"></i>

                </span>

                <span class="sidebar-item-text">

                    ' . $item['title'] . '

                </span>

                ' . $rightContent . '

            </a>

        </li>
    ';
}