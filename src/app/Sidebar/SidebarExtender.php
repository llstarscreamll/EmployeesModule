<?php

namespace llstarscreamll\EmployeesModule\app\Sidebar;

use Maatwebsite\Sidebar\Badge;
use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;

class SidebarExtender implements \Maatwebsite\Sidebar\SidebarExtender
{
    /**
     * @param Menu $menu
     *
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group('', function (Group $group) {
            $group->item('Empleados', function (Item $item) {
                $item->icon('fa fa-users');
                $item->weight(1);
                $item->route('employee.index');
                $item->authorize(
                    \Auth::user()->can('employee.index')
                );
            });
        });

        return $menu;
    }
}
