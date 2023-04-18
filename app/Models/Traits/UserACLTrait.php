<?php


namespace App\Models\Traits;


trait UserACLTrait
{
    public function permissions()
    {
        $perfil = $this->profile()->first();

        $permissions = $perfil->permissions()->where('acesso', 1)->get();

        $rules = [];
        foreach ($permissions as $permission) {
            $rule = $permission->regras()->get();
            array_push($rules, $rule->first()->nome);
        }
        return $rules;
    }

    public function hasPermission(string $permissionName): bool
    {
        return in_array($permissionName, $this->permissions());
    }

    public function isRoot(): bool
    {
        return in_array($this->cpf, config("acl.roots"));
    }

    public function montaMenu()
    {
        $menu = [];
        $menus = config("manserv.menu");

        foreach ($menus as $m) {
            $submenus = [];
            foreach ($m['submenu'] as $item) {
                if ($this->isRoot()) {
                    array_push($submenus, $item);
                } else if ($this->hasPermission($item['can'])){
                    array_push($submenus, $item);
                }
            }
            if (!in_array($m['text'], $menu)) {
                $menu[$m['text']] = [
                    'text' => $m['text'],
                    'icon' => $m['icon'],
                    'label' => $m['label'],
                    'submenu' => $submenus,
                ];
            }
        }
        return $menu;
    }
}