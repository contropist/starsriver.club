
	动态部分大体已经完成，部分细节还没有修改。
	分享部分的模板全部通过语言文件 lang_feed.php -> feed_share***  来实现。
	
	
	接下来要改的：

		动态区：
			侧栏滚动悬停
			系统模板全部使用语言文件
			首页评论部分道具和彩虹使用后的效果
			各种小细节
			-分享区各种分享类型样式模板
			-话题区的ajax自动加载
			-话题发布显示模板修改：forum\ajax_followpost.htm
			
		文章列表：
			根据插件[1314]列表页缩略图 2.6.6 hook.class.php，增加文章列表图片显示。
			列表样式优化
			板块头部的简介和注意事项修复
		
		社团：
			社团页面大图显示修改为 rest风,太丑了
		
		广场：
			广场订阅和专辑样式修改，增加更多按钮。
		
		消息通知部分：
			不少删除，忽略（黑名单）的操作不能用
		
		全局：
			悬浮菜单错位问题。
			
		个人页面：
			还没有开始。
			
		门户及门户管理
			还没有开始。
		

















<!--{template home/space_home_header}-->
<!--{if empty($nestmode)}-->
	<div class="plate layout-0">
		<!--[nest=nestcontenttop]--><div id="nestcontenttop" class="area"></div><!--[/nest]-->
	</div>
	<!--{if $_G[setting][homestyle]}-->
		<div class="plate layout-3-type2 soft">
			<section>
				<!--{subtemplate home/space_home_side_left}-->
			</section>
			<section>
				<!--[nest=nest1]--><div id="nest1" class="area"></div><!--[/nest]-->
				<!--[nest=nest2]--><div id="nest2" class="area"></div><!--[/nest]-->
				<!--[nest=nest3]--><div id="nest3" class="area"></div><!--[/nest]-->
				<!--[nest=nestcontentbottom]--><div id="nestcontentbottom" class="area"></div><!--[/nest]-->
			</section>
			<section>
				<!--{subtemplate home/space_home_side_right}-->
			</section>
		</div>
	<!--{else}-->
		<div class="please-wait-later">
			本部件已放弃治疗
		</div>
	<!--{/if}-->
<!--{else}-->
	<!--{if $_G[setting][homepagestyle]}-->
		<div class="plate layout-2-type2 soft">
			<section class="col-1">
			</section>
			<section class="col-2">
				<div class="vessel">
					<!--{subtemplate home/space_userabout}-->
				</div>
			</section>
		</div>
	<!--{else}-->
		<div class="please-wait-later">
			本部件已放弃治疗
		</div>
	<!--{/if}-->
<!--{/if}-->