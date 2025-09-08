<?php

namespace App\Permissions\V1;

use App\Models\User;

final class Abilities
{
    public const ViewAnyProducts = 'products:viewAny';

    public const ViewProducts = 'products:view';

    public const CreateProducts = 'products:create';

    public const UpdateProducts = 'products:update';

    public const DeleteProducts = 'products:delete';

    public const ViewAnyCategories = 'categories:viewAny';

    public const ViewCategories = 'categories:view';

    public const CreateCategories = 'categories:create';

    public const UpdateCategories = 'categories:update';

    public const DeleteCategories = 'categories:delete';

    /**
     * @return string[]
     */
    public static function getAbilities(User $user): array
    {
        if ($user->isAdmin()) {
            return [
                self::ViewAnyCategories,
                self::ViewCategories,
                self::CreateCategories,
                self::UpdateCategories,
                self::DeleteCategories,
                self::ViewAnyProducts,
                self::ViewProducts,
                self::CreateProducts,
                self::UpdateProducts,
                self::DeleteProducts,
            ];
        } else {
            return [
                self::ViewAnyCategories,
                self::ViewCategories,
                self::ViewAnyCategories,
                self::CreateCategories,
            ];
        }
    }
}
