<h5 class="mt-2">
    <span id="totalUsers">{$Users|default:array()|count}</span>
    {translate key=Users} <span class="d-none user-permission-spinner">
        <div class="spinner-border spinner-border-sm" role="status"></div>
    </span>
</h5>
<table class="table table-striped">
    <tbody>
        {foreach from=$Users item=u}
            <tr>
                <td>
                    <div class="form-group clearfix">
                        <label for="permission{$u->Id}" class="float-start">{fullname first=$u->First|unescape:'html' last=$u->Last|unescape:'html'}</label>
                        <select class="change-permission-type float-end form-select form-select-sm" style="width:auto;"
                            id="permission{$u->Id}" data-user-id="{$u->Id}">
                            <option value="{ResourcePermissionType::None}" class="none"
                                {if $u->PermissionType == ResourcePermissionType::None}selected="selected" {/if}>
                                {translate key=None}
                            </option>
                            <option value="{ResourcePermissionType::Full}" class="full"
                                {if $u->PermissionType == ResourcePermissionType::Full}selected="selected" {/if}>
                                {translate key=FullAccess}
                            </option>
                            <option value="{ResourcePermissionType::View}" class="view"
                                {if $u->PermissionType == ResourcePermissionType::View}selected="selected" {/if}>
                                {translate key=ViewOnly}
                            </option>
                        </select>
                        <input type="hidden" class="id" value="{$u->Id}" />
                    </div>
                </td>
            </tr>
        {/foreach}
    </tbody>
</table>
