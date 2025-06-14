<h5 class="mt-2">
    <span id="totalGroups">{$Groups|default:array()|count}</span> {translate key=Groups} <span
        class="d-none group-permission-spinner">
        <div class="spinner-border spinner-border-sm" role="status"></div>
    </span>
</h5>
<table class="table table-striped">
    <tbody>
        {foreach from=$Groups item=g}
            <tr>
                <td>
                    <div class="form-group clearfix">
                        <label for="permission{$g->Id}" class="float-start">{$g->Name}</label>
                        <select class="change-permission-type float-end form-select form-select-sm inline-block"
                            style="width:auto;" id="permission{$g->Id}" data-group-id="{$g->Id}">
                            <option value="{ResourcePermissionType::None}" class="none"
                                {if $g->PermissionType == ResourcePermissionType::None}selected="selected" {/if}>
                                {translate key=None}
                            </option>
                            <option value="{ResourcePermissionType::Full}" class="full"
                                {if $g->PermissionType == ResourcePermissionType::Full}selected="selected" {/if}>
                                {translate key=FullAccess}
                            </option>
                            <option value="{ResourcePermissionType::View}" class="view"
                                {if $g->PermissionType == ResourcePermissionType::View}selected="selected" {/if}>
                                {translate key=ViewOnly}
                            </option>
                        </select>
                        <input type="hidden" class="id" value="{$g->Id}" />
                    </div>
                </td>
            </tr>
        {/foreach}
    </tbody>
</table>