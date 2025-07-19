<?php

class UserGroupHelper
{
    /**
     * Checks if a user is a member of a specific group
     *
     * @param string|int $groupId The group ID to check
     * @param string|int $userId The user ID to check
     * @return bool True if user is in group, false otherwise
     */
    public static function isUserInGroup(string|int $groupId, string|int $userId): bool
    {
        $groupRepository = new GroupRepository();
        $group = $groupRepository->LoadById($groupId);
        foreach ($group->UserIds() as $groupUserId) {
            if ($groupUserId == $userId) {
                return true;
            }
        }
        return false;
    }
}
