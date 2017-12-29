<form id="pagerForm" method="post" action="{$url}">
	<input type="hidden" name="pageNum" value="{$app->post->pageNum}" />
	<input type="hidden" name="numPerPage" value="{$app->post->numPerPage}" />
	<input type="hidden" name="objectType" value="{$app->post->objectType}" />
	<input type="hidden" name="objectID" value="{$app->post->objectID}" />
</form>


<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="{$url}" method="post">     
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					<select class="combox" name="objectType">
						<option value="">全部对象类型</option>
						{foreach from=$lang->action->objectTypeMap key=key item=value}
							<option value="{$key}"{if $app->post->objectType eq $key} selected{/if}>{$value}</option>
						{/foreach}
					</select>
				</td>
				<td>
					对象ID：<input type="text" name="objectID" value="{$app->post->objectID}"/>
				</td>
			</tr>
		</table>
		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>
			</ul>
		</div>
	</div>
	</form>
</div>

<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="icon" href="<?php echo helper::createLink('action','detail',array('actionID'=>'{ldelim}sid_action{rdelim}')); ?>" target="dialog"><span>详情</span></a></li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
          <th>{$lang->id}</th>
          <th>{$lang->action->objectType}</th>
          <th>{$lang->action->objectID}</th>
          <th>{$lang->action->actor}</th>
          <th>{$lang->action->action}</th>
          <th>{$lang->action->date}</th>
          <th>{$lang->action->ip}</th>
          <th>{$lang->action->comment}</th>
			</tr>
		</thead>
		<tbody>
         {foreach  from=$actions item=action}
        <tr target="sid_action" rel="{$action->id}">
          <td>{$action->id}</td>
          <td>{if $objectTypeMap[$action->objectType]}{$objectTypeMap[{$action->objectType}]}{/if}</td>
          <td>{if $action->objectID}{$action->objectID}{/if}</td>
          <td>{if $action->actor}{$userMap[$action->actor]}{/if}</td>
          <td>{if $actionMap[$action->action]}{$actionMap[$action->action]}{else}{$action->action}{/if}</td>
          <td>{$action->date}</td>
          <td>{$action->ip}</td>
          <td>{if strlen($action->comment) > 50 }{$action->comment|substr:0:50}'...';{else}{$action->comment}{/if}</td>
        </tr>
        {/foreach}
		</tbody>
	</table>
	<div class="panelBar">
		<div class="pages">
			<span>每页</span>
			<select class="combox" name="numPerPage" onchange="navTabPageBreak({ldelim}numPerPage:this.value{rdelim})">
				{foreach from=$pager->recPerPageMap item=value}
				<option value="{$value}"{if $app->post->numPerPage == $value} selected{/if}>{$value}</option>
				{/foreach}
			</select>
			<span>条，共{$pager->recTotal}条</span>
		</div>		
		<div class="pagination" targetType="navTab" totalCount="{$pager->recTotal}" numPerPage="{$pager->recPerPage}" pageNumShown="10" currentPage="{$app->post->pageNum}"></div>
	</div>
</div>
