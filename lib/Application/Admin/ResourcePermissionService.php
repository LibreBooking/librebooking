<?php

require_once(ROOT_DIR . 'Domain/Values/ResourcePermissionType.php');

interface IResourcePermissionService
{
    /**
     * Get the resource IDs that the given user is allowed to administer.
     *
     * @return int[]
     */
    public function GetResourceIdsThatUserCanAdminister(User $adminUser): array;

    /**
     * Parse submitted permission strings (e.g. "1_0", "2_1") into separate
     * full-access and view-only resource ID arrays, filtering to only resources
     * the admin can manage.
     *
     * @param string[]|null $submittedPermissions raw form data
     * @param int[] $managedResourceIds resources the admin can manage
     * @return array{full: int[], view: int[]}
     */
    public function ParseSubmittedPermissions(
        ?array $submittedPermissions,
        array $managedResourceIds,
    ): array;

    /**
     * Compute the complete permission lists by merging admin-submitted permissions
     * with existing permissions on resources outside the admin's scope.
     *
     * @param int[] $existingIds current permission IDs (full or view)
     * @param int[] $managedResourceIds resources the admin can manage
     * @param int[] $submittedIds IDs the admin submitted for this permission type
     * @return int[]
     */
    public function MergeWithPreservedPermissions(
        array $existingIds,
        array $managedResourceIds,
        array $submittedIds,
    ): array;
}

class ResourcePermissionService implements IResourcePermissionService
{
    public function __construct(
        private IResourceRepository $resourceRepository,
    ) {
    }

    public function GetResourceIdsThatUserCanAdminister(User $adminUser): array
    {
        $managedResourceIds = [];
        $allResources = $this->resourceRepository->GetResourceList();
        foreach ($allResources as $resource) {
            if ($adminUser->IsResourceAdminFor($resource)) {
                $managedResourceIds[] = (int) $resource->GetId();
            }
        }
        return $managedResourceIds;
    }

    // The permissions form submits an array of strings like "1_0", "2_1", "3_none"
    // where the first part is the resource ID and the second is the permission type
    // (0 = Full Access, 1 = View Only, "none" = no permission).
    // This method parses those strings, discards any resources the admin cannot
    // manage, rejects malformed input, and returns the resource IDs grouped by type.
    public function ParseSubmittedPermissions(
        ?array $submittedPermissions,
        array $managedResourceIds,
    ): array {
        $fullAccessResourceIds = [];
        $viewOnlyResourceIds = [];

        if (!is_array($submittedPermissions)) {
            return ['full' => $fullAccessResourceIds, 'view' => $viewOnlyResourceIds];
        }

        // Each entry is "{resourceId}_{permissionType}" e.g. "1_0" for full, "2_1" for view
        foreach ($submittedPermissions as $permissionString) {
            if (!is_scalar($permissionString)) {
                continue;
            }
            $split = explode('_', (string) $permissionString);
            $resourceId = (int) $split[0];
            $permissionType = (string) ($split[1] ?? 'none');

            // Only allow changes to resources the current admin can administer
            if (!in_array($resourceId, $managedResourceIds, true)) {
                continue;
            }

            // Only accept valid numeric permission types; ignore malformed input
            // (e.g. "abc" would cast to 0 via (int), falsely matching Full Access)
            if (!ctype_digit($permissionType)) {
                continue;
            }

            $permissionType = (int) $permissionType;

            if ($permissionType === ResourcePermissionType::Full) {
                $fullAccessResourceIds[] = $resourceId;
            } elseif ($permissionType === ResourcePermissionType::View) {
                $viewOnlyResourceIds[] = $resourceId;
            }
        }

        return ['full' => $fullAccessResourceIds, 'view' => $viewOnlyResourceIds];
    }

    // An admin can only change permissions for resources they manage.
    // Existing permissions on resources outside their scope must be kept as-is.
    // This method combines those preserved permissions with the admin's new
    // submissions to produce the complete permission list for a given type
    // (full or view).
    //
    // Example: admin manages [1, 2, 3], user has full access to [1, 5, 10],
    // admin submits [1, 3]:
    //   $existingIds = [1, 5, 10]
    //   $managedResourceIds = [1, 2, 3]
    //   $submittedIds = [1, 3]
    //   $outsideAdminScope = [5, 10]  (admin can't touch these, preserved)
    //   result = [5, 10, 1, 3]
    public function MergeWithPreservedPermissions(
        array $existingIds,
        array $managedResourceIds,
        array $submittedIds,
    ): array {
        $outsideAdminScope = array_diff($existingIds, $managedResourceIds);
        return array_unique(array_merge($outsideAdminScope, $submittedIds));
    }
}
