<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Page\UniversalFunctions;
use \Catalyst\Page\Values;
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::EMOJI[0]);
define("PAGE_TITLE", Values::createTitle(Values::EMOJI[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Emoji");
?>
			<div class="section">
				<p class="flow-text">Hover over or tap an emoji below to see its identifier.</p>
			</div>
			<div class="divider"></div>
			<div class="section">
				<div class="flow-text">
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":grinning:">
					😀
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:grinning:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":smiley:">
					😃
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:smiley:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":smile:">
					😄
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:smile:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":grin:">
					😁
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:grin:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":laughing:">
					😆
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:laughing:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":satisfied:">
					😆
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:satisfied:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sweat_smile:">
					😅
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sweat_smile:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":joy:">
					😂
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:joy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rofl:">
					🤣
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rofl:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":relaxed:">
					☺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:relaxed:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":blush:">
					😊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:blush:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":innocent:">
					😇
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:innocent:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":slightly_smiling_face:">
					🙂
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:slightly_smiling_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":upside_down_face:">
					🙃
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:upside_down_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":wink:">
					😉
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:wink:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":relieved:">
					😌
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:relieved:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":heart_eyes:">
					😍
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:heart_eyes:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":kissing_heart:">
					😘
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:kissing_heart:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":kissing:">
					😗
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:kissing:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":kissing_smiling_eyes:">
					😙
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:kissing_smiling_eyes:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":kissing_closed_eyes:">
					😚
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:kissing_closed_eyes:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":yum:">
					😋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:yum:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":stuck_out_tongue_winking_eye:">
					😜
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:stuck_out_tongue_winking_eye:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":stuck_out_tongue_closed_eyes:">
					😝
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:stuck_out_tongue_closed_eyes:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":stuck_out_tongue:">
					😛
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:stuck_out_tongue:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":money_mouth_face:">
					🤑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:money_mouth_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hugs:">
					🤗
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hugs:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":nerd_face:">
					🤓
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:nerd_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sunglasses:">
					😎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sunglasses:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clown_face:">
					🤡
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clown_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cowboy_hat_face:">
					🤠
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cowboy_hat_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":smirk:">
					😏
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:smirk:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":unamused:">
					😒
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:unamused:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":disappointed:">
					😞
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:disappointed:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pensive:">
					😔
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pensive:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":worried:">
					😟
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:worried:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":confused:">
					😕
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:confused:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":slightly_frowning_face:">
					🙁
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:slightly_frowning_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":frowning_face:">
					☹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:frowning_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":persevere:">
					😣
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:persevere:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":confounded:">
					😖
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:confounded:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tired_face:">
					😫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tired_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":weary:">
					😩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:weary:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":triumph:">
					😤
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:triumph:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":angry:">
					😠
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:angry:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rage:">
					😡
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rage:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pout:">
					😡
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pout:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":no_mouth:">
					😶
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:no_mouth:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":neutral_face:">
					😐
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:neutral_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":expressionless:">
					😑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:expressionless:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hushed:">
					😯
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hushed:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":frowning:">
					😦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:frowning:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":anguished:">
					😧
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:anguished:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":open_mouth:">
					😮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:open_mouth:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":astonished:">
					😲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:astonished:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dizzy_face:">
					😵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dizzy_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":flushed:">
					😳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:flushed:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":scream:">
					😱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:scream:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fearful:">
					😨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fearful:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cold_sweat:">
					😰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cold_sweat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cry:">
					😢
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cry:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":disappointed_relieved:">
					😥
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:disappointed_relieved:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":drooling_face:">
					🤤
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:drooling_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sob:">
					😭
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sob:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sweat:">
					😓
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sweat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sleepy:">
					😪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sleepy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sleeping:">
					😴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sleeping:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":roll_eyes:">
					🙄
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:roll_eyes:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":thinking:">
					🤔
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:thinking:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":lying_face:">
					🤥
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:lying_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":grimacing:">
					😬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:grimacing:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":zipper_mouth_face:">
					🤐
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:zipper_mouth_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":nauseated_face:">
					🤢
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:nauseated_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sneezing_face:">
					🤧
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sneezing_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mask:">
					😷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mask:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":face_with_thermometer:">
					🤒
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:face_with_thermometer:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":face_with_head_bandage:">
					🤕
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:face_with_head_bandage:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":smiling_imp:">
					😈
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:smiling_imp:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":imp:">
					👿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:imp:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":japanese_ogre:">
					👹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:japanese_ogre:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":japanese_goblin:">
					👺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:japanese_goblin:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hankey:">
					💩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hankey:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":poop:">
					💩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:poop:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":shit:">
					💩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:shit:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ghost:">
					👻
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ghost:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":skull:">
					💀
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:skull:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":skull_and_crossbones:">
					☠
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:skull_and_crossbones:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":alien:">
					👽
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:alien:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":space_invader:">
					👾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:space_invader:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":robot:">
					🤖
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:robot:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":jack_o_lantern:">
					🎃
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:jack_o_lantern:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":smiley_cat:">
					😺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:smiley_cat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":smile_cat:">
					😸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:smile_cat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":joy_cat:">
					😹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:joy_cat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":heart_eyes_cat:">
					😻
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:heart_eyes_cat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":smirk_cat:">
					😼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:smirk_cat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":kissing_cat:">
					😽
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:kissing_cat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":scream_cat:">
					🙀
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:scream_cat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":crying_cat_face:">
					😿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:crying_cat_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pouting_cat:">
					😾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pouting_cat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":open_hands:">
					👐
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:open_hands:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":raised_hands:">
					🙌
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:raised_hands:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clap:">
					👏
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clap:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pray:">
					🙏
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pray:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":handshake:">
					🤝
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:handshake:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":+1:">
					👍
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:+1:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":thumbsup:">
					👍
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:thumbsup:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":-1:">
					👎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:-1:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":thumbsdown:">
					👎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:thumbsdown:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fist_oncoming:">
					👊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fist_oncoming:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":facepunch:">
					👊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:facepunch:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":punch:">
					👊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:punch:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fist_raised:">
					✊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fist_raised:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fist:">
					✊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fist:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fist_left:">
					🤛
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fist_left:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fist_right:">
					🤜
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fist_right:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":crossed_fingers:">
					🤞
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:crossed_fingers:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":v:">
					✌
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:v:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":metal:">
					🤘
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:metal:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ok_hand:">
					👌
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ok_hand:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":point_left:">
					👈
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:point_left:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":point_right:">
					👉
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:point_right:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":point_up_2:">
					👆
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:point_up_2:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":point_down:">
					👇
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:point_down:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":point_up:">
					☝
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:point_up:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hand:">
					✋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hand:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":raised_hand:">
					✋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:raised_hand:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":raised_back_of_hand:">
					🤚
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:raised_back_of_hand:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":raised_hand_with_fingers_splayed:">
					🖐
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:raised_hand_with_fingers_splayed:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":vulcan_salute:">
					🖖
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:vulcan_salute:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":wave:">
					👋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:wave:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":call_me_hand:">
					🤙
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:call_me_hand:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":muscle:">
					💪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:muscle:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":middle_finger:">
					🖕
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:middle_finger:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fu:">
					🖕
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fu:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":writing_hand:">
					✍
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:writing_hand:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":selfie:">
					🤳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:selfie:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":nail_care:">
					💅
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:nail_care:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ring:">
					💍
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ring:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":lipstick:">
					💄
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:lipstick:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":kiss:">
					💋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:kiss:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":lips:">
					👄
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:lips:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tongue:">
					👅
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tongue:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ear:">
					👂
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ear:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":nose:">
					👃
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:nose:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":footprints:">
					👣
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:footprints:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":eye:">
					👁
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:eye:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":eyes:">
					👀
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:eyes:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":speaking_head:">
					🗣
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:speaking_head:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bust_in_silhouette:">
					👤
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bust_in_silhouette:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":busts_in_silhouette:">
					👥
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:busts_in_silhouette:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":baby:">
					👶
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:baby:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":boy:">
					👦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:boy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":girl:">
					👧
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:girl:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":blonde_woman:">
					👱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:blonde_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":blonde_man:">
					👱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:blonde_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":person_with_blond_hair:">
					👱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:person_with_blond_hair:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":older_man:">
					👴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:older_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":older_woman:">
					👵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:older_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_with_gua_pi_mao:">
					👲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_with_gua_pi_mao:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_with_turban:">
					👳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_with_turban:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_with_turban:">
					👳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_with_turban:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":policewoman:">
					👮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:policewoman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":policeman:">
					👮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:policeman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cop:">
					👮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cop:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":construction_worker_woman:">
					👷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:construction_worker_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":construction_worker_man:">
					👷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:construction_worker_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":construction_worker:">
					👷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:construction_worker:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":guardswoman:">
					💂
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:guardswoman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":guardsman:">
					💂
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:guardsman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":female_detective:">
					🕵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:female_detective:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":male_detective:">
					🕵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:male_detective:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":detective:">
					🕵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:detective:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_health_worker:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_health_worker:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_health_worker:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_health_worker:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_farmer:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_farmer:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_farmer:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_farmer:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_cook:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_cook:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_cook:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_cook:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_student:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_student:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_student:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_student:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_singer:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_singer:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_singer:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_singer:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_teacher:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_teacher:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_teacher:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_teacher:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_factory_worker:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_factory_worker:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_factory_worker:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_factory_worker:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_technologist:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_technologist:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_technologist:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_technologist:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_office_worker:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_office_worker:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_office_worker:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_office_worker:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_mechanic:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_mechanic:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_mechanic:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_mechanic:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_scientist:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_scientist:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_scientist:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_scientist:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_artist:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_artist:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_artist:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_artist:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_firefighter:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_firefighter:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_firefighter:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_firefighter:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_pilot:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_pilot:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_pilot:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_pilot:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_astronaut:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_astronaut:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_astronaut:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_astronaut:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_judge:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_judge:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_judge:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_judge:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mrs_claus:">
					🤶
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mrs_claus:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":santa:">
					🎅
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:santa:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":princess:">
					👸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:princess:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":prince:">
					🤴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:prince:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bride_with_veil:">
					👰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bride_with_veil:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_in_tuxedo:">
					🤵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_in_tuxedo:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":angel:">
					👼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:angel:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pregnant_woman:">
					🤰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pregnant_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bowing_woman:">
					🙇
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bowing_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bowing_man:">
					🙇
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bowing_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bow:">
					🙇
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bow:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tipping_hand_woman:">
					💁
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tipping_hand_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":information_desk_person:">
					💁
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:information_desk_person:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sassy_woman:">
					💁
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sassy_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tipping_hand_man:">
					💁
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tipping_hand_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sassy_man:">
					💁
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sassy_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":no_good_woman:">
					🙅
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:no_good_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":no_good:">
					🙅
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:no_good:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ng_woman:">
					🙅
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ng_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":no_good_man:">
					🙅
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:no_good_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ng_man:">
					🙅
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ng_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ok_woman:">
					🙆
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ok_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ok_man:">
					🙆
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ok_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":raising_hand_woman:">
					🙋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:raising_hand_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":raising_hand:">
					🙋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:raising_hand:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":raising_hand_man:">
					🙋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:raising_hand_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_facepalming:">
					🤦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_facepalming:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_facepalming:">
					🤦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_facepalming:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_shrugging:">
					🤷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_shrugging:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_shrugging:">
					🤷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_shrugging:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pouting_woman:">
					🙎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pouting_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":person_with_pouting_face:">
					🙎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:person_with_pouting_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pouting_man:">
					🙎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pouting_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":frowning_woman:">
					🙍
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:frowning_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":person_frowning:">
					🙍
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:person_frowning:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":frowning_man:">
					🙍
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:frowning_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":haircut_woman:">
					💇
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:haircut_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":haircut:">
					💇
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:haircut:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":haircut_man:">
					💇
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:haircut_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":massage_woman:">
					💆
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:massage_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":massage:">
					💆
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:massage:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":massage_man:">
					💆
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:massage_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":business_suit_levitating:">
					🕴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:business_suit_levitating:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dancer:">
					💃
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dancer:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_dancing:">
					🕺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_dancing:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dancing_women:">
					👯
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dancing_women:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dancers:">
					👯
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dancers:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dancing_men:">
					👯
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dancing_men:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":walking_woman:">
					🚶
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:walking_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":walking_man:">
					🚶
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:walking_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":walking:">
					🚶
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:walking:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":running_woman:">
					🏃
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:running_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":running_man:">
					🏃
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:running_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":runner:">
					🏃
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:runner:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":running:">
					🏃
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:running:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":couple:">
					👫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:couple:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":two_women_holding_hands:">
					👭
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:two_women_holding_hands:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":two_men_holding_hands:">
					👬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:two_men_holding_hands:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":couple_with_heart_woman_man:">
					💑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:couple_with_heart_woman_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":couple_with_heart:">
					💑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:couple_with_heart:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":couple_with_heart_woman_woman:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:couple_with_heart_woman_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":couple_with_heart_man_man:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:couple_with_heart_man_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":couplekiss_man_woman:">
					💏
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:couplekiss_man_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":couplekiss_woman_woman:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:couplekiss_woman_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":couplekiss_man_man:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:couplekiss_man_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_man_woman_boy:">
					👪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_man_woman_boy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family:">
					👪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_man_woman_girl:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_man_woman_girl:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_man_woman_girl_boy:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_man_woman_girl_boy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_man_woman_boy_boy:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_man_woman_boy_boy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_man_woman_girl_girl:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_man_woman_girl_girl:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_woman_woman_boy:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_woman_woman_boy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_woman_woman_girl:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_woman_woman_girl:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_woman_woman_girl_boy:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_woman_woman_girl_boy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_woman_woman_boy_boy:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_woman_woman_boy_boy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_woman_woman_girl_girl:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_woman_woman_girl_girl:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_man_man_boy:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_man_man_boy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_man_man_girl:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_man_man_girl:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_man_man_girl_boy:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_man_man_girl_boy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_man_man_boy_boy:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_man_man_boy_boy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_man_man_girl_girl:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_man_man_girl_girl:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_woman_boy:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_woman_boy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_woman_girl:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_woman_girl:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_woman_girl_boy:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_woman_girl_boy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_woman_boy_boy:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_woman_boy_boy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_woman_girl_girl:">
					👩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_woman_girl_girl:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_man_boy:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_man_boy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_man_girl:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_man_girl:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_man_girl_boy:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_man_girl_boy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_man_boy_boy:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_man_boy_boy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":family_man_girl_girl:">
					👨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:family_man_girl_girl:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":womans_clothes:">
					👚
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:womans_clothes:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":shirt:">
					👕
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:shirt:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tshirt:">
					👕
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tshirt:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":jeans:">
					👖
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:jeans:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":necktie:">
					👔
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:necktie:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dress:">
					👗
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dress:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bikini:">
					👙
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bikini:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":kimono:">
					👘
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:kimono:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":high_heel:">
					👠
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:high_heel:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sandal:">
					👡
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sandal:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":boot:">
					👢
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:boot:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mans_shoe:">
					👞
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mans_shoe:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":shoe:">
					👞
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:shoe:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":athletic_shoe:">
					👟
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:athletic_shoe:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":womans_hat:">
					👒
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:womans_hat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tophat:">
					🎩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tophat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mortar_board:">
					🎓
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mortar_board:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":crown:">
					👑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:crown:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rescue_worker_helmet:">
					⛑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rescue_worker_helmet:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":school_satchel:">
					🎒
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:school_satchel:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pouch:">
					👝
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pouch:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":purse:">
					👛
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:purse:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":handbag:">
					👜
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:handbag:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":briefcase:">
					💼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:briefcase:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":eyeglasses:">
					👓
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:eyeglasses:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dark_sunglasses:">
					🕶
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dark_sunglasses:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":closed_umbrella:">
					🌂
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:closed_umbrella:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":open_umbrella:">
					☂
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:open_umbrella:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dog:">
					🐶
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dog:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cat:">
					🐱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mouse:">
					🐭
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mouse:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hamster:">
					🐹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hamster:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rabbit:">
					🐰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rabbit:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fox_face:">
					🦊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fox_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bear:">
					🐻
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bear:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":panda_face:">
					🐼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:panda_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":koala:">
					🐨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:koala:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tiger:">
					🐯
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tiger:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":lion:">
					🦁
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:lion:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cow:">
					🐮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cow:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pig:">
					🐷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pig:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pig_nose:">
					🐽
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pig_nose:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":frog:">
					🐸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:frog:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":monkey_face:">
					🐵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:monkey_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":see_no_evil:">
					🙈
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:see_no_evil:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hear_no_evil:">
					🙉
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hear_no_evil:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":speak_no_evil:">
					🙊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:speak_no_evil:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":monkey:">
					🐒
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:monkey:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":chicken:">
					🐔
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:chicken:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":penguin:">
					🐧
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:penguin:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bird:">
					🐦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bird:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":baby_chick:">
					🐤
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:baby_chick:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hatching_chick:">
					🐣
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hatching_chick:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hatched_chick:">
					🐥
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hatched_chick:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":duck:">
					🦆
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:duck:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":eagle:">
					🦅
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:eagle:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":owl:">
					🦉
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:owl:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bat:">
					🦇
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":wolf:">
					🐺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:wolf:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":boar:">
					🐗
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:boar:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":horse:">
					🐴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:horse:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":unicorn:">
					🦄
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:unicorn:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bee:">
					🐝
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bee:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":honeybee:">
					🐝
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:honeybee:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bug:">
					🐛
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bug:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":butterfly:">
					🦋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:butterfly:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":snail:">
					🐌
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:snail:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":shell:">
					🐚
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:shell:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":beetle:">
					🐞
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:beetle:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ant:">
					🐜
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ant:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":spider:">
					🕷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:spider:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":spider_web:">
					🕸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:spider_web:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":turtle:">
					🐢
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:turtle:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":snake:">
					🐍
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:snake:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":lizard:">
					🦎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:lizard:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":scorpion:">
					🦂
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:scorpion:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":crab:">
					🦀
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:crab:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":squid:">
					🦑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:squid:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":octopus:">
					🐙
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:octopus:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":shrimp:">
					🦐
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:shrimp:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tropical_fish:">
					🐠
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tropical_fish:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fish:">
					🐟
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fish:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":blowfish:">
					🐡
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:blowfish:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dolphin:">
					🐬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dolphin:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":flipper:">
					🐬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:flipper:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":shark:">
					🦈
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:shark:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":whale:">
					🐳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:whale:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":whale2:">
					🐋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:whale2:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":crocodile:">
					🐊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:crocodile:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":leopard:">
					🐆
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:leopard:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tiger2:">
					🐅
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tiger2:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":water_buffalo:">
					🐃
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:water_buffalo:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ox:">
					🐂
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ox:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cow2:">
					🐄
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cow2:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":deer:">
					🦌
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:deer:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dromedary_camel:">
					🐪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dromedary_camel:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":camel:">
					🐫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:camel:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":elephant:">
					🐘
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:elephant:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rhinoceros:">
					🦏
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rhinoceros:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":gorilla:">
					🦍
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:gorilla:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":racehorse:">
					🐎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:racehorse:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pig2:">
					🐖
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pig2:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":goat:">
					🐐
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:goat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ram:">
					🐏
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ram:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sheep:">
					🐑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sheep:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dog2:">
					🐕
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dog2:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":poodle:">
					🐩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:poodle:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cat2:">
					🐈
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cat2:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rooster:">
					🐓
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rooster:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":turkey:">
					🦃
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:turkey:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dove:">
					🕊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dove:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rabbit2:">
					🐇
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rabbit2:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mouse2:">
					🐁
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mouse2:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rat:">
					🐀
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":chipmunk:">
					🐿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:chipmunk:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":feet:">
					🐾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:feet:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":paw_prints:">
					🐾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:paw_prints:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dragon:">
					🐉
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dragon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dragon_face:">
					🐲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dragon_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cactus:">
					🌵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cactus:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":christmas_tree:">
					🎄
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:christmas_tree:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":evergreen_tree:">
					🌲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:evergreen_tree:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":deciduous_tree:">
					🌳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:deciduous_tree:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":palm_tree:">
					🌴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:palm_tree:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":seedling:">
					🌱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:seedling:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":herb:">
					🌿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:herb:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":shamrock:">
					☘
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:shamrock:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":four_leaf_clover:">
					🍀
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:four_leaf_clover:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bamboo:">
					🎍
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bamboo:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tanabata_tree:">
					🎋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tanabata_tree:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":leaves:">
					🍃
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:leaves:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fallen_leaf:">
					🍂
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fallen_leaf:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":maple_leaf:">
					🍁
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:maple_leaf:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mushroom:">
					🍄
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mushroom:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ear_of_rice:">
					🌾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ear_of_rice:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bouquet:">
					💐
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bouquet:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tulip:">
					🌷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tulip:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rose:">
					🌹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rose:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":wilted_flower:">
					🥀
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:wilted_flower:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sunflower:">
					🌻
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sunflower:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":blossom:">
					🌼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:blossom:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cherry_blossom:">
					🌸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cherry_blossom:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hibiscus:">
					🌺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hibiscus:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":earth_americas:">
					🌎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:earth_americas:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":earth_africa:">
					🌍
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:earth_africa:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":earth_asia:">
					🌏
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:earth_asia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":full_moon:">
					🌕
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:full_moon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":waning_gibbous_moon:">
					🌖
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:waning_gibbous_moon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":last_quarter_moon:">
					🌗
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:last_quarter_moon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":waning_crescent_moon:">
					🌘
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:waning_crescent_moon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":new_moon:">
					🌑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:new_moon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":waxing_crescent_moon:">
					🌒
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:waxing_crescent_moon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":first_quarter_moon:">
					🌓
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:first_quarter_moon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":moon:">
					🌔
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:moon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":waxing_gibbous_moon:">
					🌔
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:waxing_gibbous_moon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":new_moon_with_face:">
					🌚
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:new_moon_with_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":full_moon_with_face:">
					🌝
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:full_moon_with_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sun_with_face:">
					🌞
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sun_with_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":first_quarter_moon_with_face:">
					🌛
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:first_quarter_moon_with_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":last_quarter_moon_with_face:">
					🌜
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:last_quarter_moon_with_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":crescent_moon:">
					🌙
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:crescent_moon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dizzy:">
					💫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dizzy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":star:">
					⭐
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:star:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":star2:">
					🌟
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:star2:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sparkles:">
					✨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sparkles:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":zap:">
					⚡
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:zap:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fire:">
					🔥
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fire:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":boom:">
					💥
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:boom:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":collision:">
					💥
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:collision:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":comet:">
					☄
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:comet:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sunny:">
					☀
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sunny:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sun_behind_small_cloud:">
					🌤
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sun_behind_small_cloud:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":partly_sunny:">
					⛅
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:partly_sunny:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sun_behind_large_cloud:">
					🌥
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sun_behind_large_cloud:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sun_behind_rain_cloud:">
					🌦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sun_behind_rain_cloud:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rainbow:">
					🌈
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rainbow:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cloud:">
					☁
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cloud:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cloud_with_rain:">
					🌧
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cloud_with_rain:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cloud_with_lightning_and_rain:">
					⛈
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cloud_with_lightning_and_rain:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cloud_with_lightning:">
					🌩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cloud_with_lightning:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cloud_with_snow:">
					🌨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cloud_with_snow:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":snowman_with_snow:">
					☃
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:snowman_with_snow:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":snowman:">
					⛄
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:snowman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":snowflake:">
					❄
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:snowflake:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":wind_face:">
					🌬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:wind_face:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dash:">
					💨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dash:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tornado:">
					🌪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tornado:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fog:">
					🌫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fog:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ocean:">
					🌊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ocean:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":droplet:">
					💧
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:droplet:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sweat_drops:">
					💦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sweat_drops:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":umbrella:">
					☔
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:umbrella:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":green_apple:">
					🍏
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:green_apple:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":apple:">
					🍎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:apple:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pear:">
					🍐
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pear:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tangerine:">
					🍊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tangerine:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":orange:">
					🍊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:orange:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mandarin:">
					🍊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mandarin:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":lemon:">
					🍋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:lemon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":banana:">
					🍌
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:banana:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":watermelon:">
					🍉
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:watermelon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":grapes:">
					🍇
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:grapes:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":strawberry:">
					🍓
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:strawberry:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":melon:">
					🍈
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:melon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cherries:">
					🍒
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cherries:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":peach:">
					🍑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:peach:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pineapple:">
					🍍
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pineapple:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":kiwi_fruit:">
					🥝
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:kiwi_fruit:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":avocado:">
					🥑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:avocado:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tomato:">
					🍅
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tomato:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":eggplant:">
					🍆
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:eggplant:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cucumber:">
					🥒
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cucumber:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":carrot:">
					🥕
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:carrot:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":corn:">
					🌽
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:corn:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hot_pepper:">
					🌶
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hot_pepper:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":potato:">
					🥔
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:potato:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sweet_potato:">
					🍠
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sweet_potato:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":chestnut:">
					🌰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:chestnut:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":peanuts:">
					🥜
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:peanuts:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":honey_pot:">
					🍯
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:honey_pot:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":croissant:">
					🥐
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:croissant:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bread:">
					🍞
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bread:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":baguette_bread:">
					🥖
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:baguette_bread:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cheese:">
					🧀
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cheese:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":egg:">
					🥚
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:egg:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fried_egg:">
					🍳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fried_egg:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bacon:">
					🥓
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bacon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pancakes:">
					🥞
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pancakes:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fried_shrimp:">
					🍤
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fried_shrimp:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":poultry_leg:">
					🍗
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:poultry_leg:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":meat_on_bone:">
					🍖
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:meat_on_bone:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pizza:">
					🍕
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pizza:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hotdog:">
					🌭
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hotdog:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hamburger:">
					🍔
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hamburger:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fries:">
					🍟
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fries:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":stuffed_flatbread:">
					🥙
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:stuffed_flatbread:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":taco:">
					🌮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:taco:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":burrito:">
					🌯
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:burrito:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":green_salad:">
					🥗
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:green_salad:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":shallow_pan_of_food:">
					🥘
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:shallow_pan_of_food:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":spaghetti:">
					🍝
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:spaghetti:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ramen:">
					🍜
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ramen:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":stew:">
					🍲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:stew:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fish_cake:">
					🍥
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fish_cake:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sushi:">
					🍣
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sushi:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bento:">
					🍱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bento:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":curry:">
					🍛
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:curry:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rice:">
					🍚
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rice:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rice_ball:">
					🍙
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rice_ball:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rice_cracker:">
					🍘
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rice_cracker:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":oden:">
					🍢
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:oden:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dango:">
					🍡
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dango:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":shaved_ice:">
					🍧
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:shaved_ice:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ice_cream:">
					🍨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ice_cream:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":icecream:">
					🍦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:icecream:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cake:">
					🍰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cake:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":birthday:">
					🎂
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:birthday:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":custard:">
					🍮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:custard:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":lollipop:">
					🍭
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:lollipop:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":candy:">
					🍬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:candy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":chocolate_bar:">
					🍫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:chocolate_bar:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":popcorn:">
					🍿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:popcorn:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":doughnut:">
					🍩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:doughnut:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cookie:">
					🍪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cookie:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":milk_glass:">
					🥛
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:milk_glass:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":baby_bottle:">
					🍼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:baby_bottle:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":coffee:">
					☕
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:coffee:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tea:">
					🍵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tea:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sake:">
					🍶
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sake:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":beer:">
					🍺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:beer:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":beers:">
					🍻
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:beers:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clinking_glasses:">
					🥂
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clinking_glasses:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":wine_glass:">
					🍷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:wine_glass:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tumbler_glass:">
					🥃
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tumbler_glass:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cocktail:">
					🍸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cocktail:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tropical_drink:">
					🍹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tropical_drink:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":champagne:">
					🍾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:champagne:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":spoon:">
					🥄
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:spoon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fork_and_knife:">
					🍴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fork_and_knife:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":plate_with_cutlery:">
					🍽
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:plate_with_cutlery:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":soccer:">
					⚽
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:soccer:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":basketball:">
					🏀
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:basketball:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":football:">
					🏈
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:football:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":baseball:">
					⚾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:baseball:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tennis:">
					🎾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tennis:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":volleyball:">
					🏐
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:volleyball:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rugby_football:">
					🏉
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rugby_football:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":8ball:">
					🎱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:8ball:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ping_pong:">
					🏓
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ping_pong:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":badminton:">
					🏸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:badminton:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":goal_net:">
					🥅
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:goal_net:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ice_hockey:">
					🏒
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ice_hockey:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":field_hockey:">
					🏑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:field_hockey:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cricket:">
					🏏
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cricket:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":golf:">
					⛳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:golf:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bow_and_arrow:">
					🏹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bow_and_arrow:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fishing_pole_and_fish:">
					🎣
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fishing_pole_and_fish:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":boxing_glove:">
					🥊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:boxing_glove:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":martial_arts_uniform:">
					🥋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:martial_arts_uniform:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ice_skate:">
					⛸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ice_skate:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ski:">
					🎿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ski:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":skier:">
					⛷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:skier:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":snowboarder:">
					🏂
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:snowboarder:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":weight_lifting_woman:">
					🏋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:weight_lifting_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":weight_lifting_man:">
					🏋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:weight_lifting_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":person_fencing:">
					🤺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:person_fencing:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":women_wrestling:">
					🤼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:women_wrestling:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":men_wrestling:">
					🤼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:men_wrestling:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_cartwheeling:">
					🤸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_cartwheeling:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_cartwheeling:">
					🤸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_cartwheeling:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":basketball_woman:">
					⛹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:basketball_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":basketball_man:">
					⛹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:basketball_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_playing_handball:">
					🤾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_playing_handball:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_playing_handball:">
					🤾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_playing_handball:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":golfing_woman:">
					🏌
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:golfing_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":golfing_man:">
					🏌
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:golfing_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":surfing_woman:">
					🏄
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:surfing_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":surfing_man:">
					🏄
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:surfing_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":surfer:">
					🏄
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:surfer:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":swimming_woman:">
					🏊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:swimming_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":swimming_man:">
					🏊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:swimming_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":swimmer:">
					🏊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:swimmer:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_playing_water_polo:">
					🤽
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_playing_water_polo:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_playing_water_polo:">
					🤽
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_playing_water_polo:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rowing_woman:">
					🚣
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rowing_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rowing_man:">
					🚣
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rowing_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rowboat:">
					🚣
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rowboat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":horse_racing:">
					🏇
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:horse_racing:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":biking_woman:">
					🚴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:biking_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":biking_man:">
					🚴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:biking_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bicyclist:">
					🚴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bicyclist:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mountain_biking_woman:">
					🚵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mountain_biking_woman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mountain_biking_man:">
					🚵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mountain_biking_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mountain_bicyclist:">
					🚵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mountain_bicyclist:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":running_shirt_with_sash:">
					🎽
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:running_shirt_with_sash:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":medal_sports:">
					🏅
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:medal_sports:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":medal_military:">
					🎖
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:medal_military:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":1st_place_medal:">
					🥇
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:1st_place_medal:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":2nd_place_medal:">
					🥈
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:2nd_place_medal:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":3rd_place_medal:">
					🥉
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:3rd_place_medal:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":trophy:">
					🏆
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:trophy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rosette:">
					🏵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rosette:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":reminder_ribbon:">
					🎗
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:reminder_ribbon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ticket:">
					🎫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ticket:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tickets:">
					🎟
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tickets:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":circus_tent:">
					🎪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:circus_tent:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":woman_juggling:">
					🤹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:woman_juggling:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":man_juggling:">
					🤹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:man_juggling:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":performing_arts:">
					🎭
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:performing_arts:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":art:">
					🎨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:art:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clapper:">
					🎬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clapper:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":microphone:">
					🎤
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:microphone:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":headphones:">
					🎧
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:headphones:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":musical_score:">
					🎼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:musical_score:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":musical_keyboard:">
					🎹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:musical_keyboard:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":drum:">
					🥁
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:drum:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":saxophone:">
					🎷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:saxophone:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":trumpet:">
					🎺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:trumpet:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":guitar:">
					🎸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:guitar:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":violin:">
					🎻
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:violin:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":game_die:">
					🎲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:game_die:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dart:">
					🎯
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dart:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bowling:">
					🎳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bowling:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":video_game:">
					🎮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:video_game:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":slot_machine:">
					🎰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:slot_machine:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":car:">
					🚗
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:car:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":red_car:">
					🚗
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:red_car:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":taxi:">
					🚕
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:taxi:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":blue_car:">
					🚙
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:blue_car:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bus:">
					🚌
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bus:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":trolleybus:">
					🚎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:trolleybus:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":racing_car:">
					🏎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:racing_car:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":police_car:">
					🚓
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:police_car:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ambulance:">
					🚑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ambulance:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fire_engine:">
					🚒
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fire_engine:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":minibus:">
					🚐
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:minibus:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":truck:">
					🚚
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:truck:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":articulated_lorry:">
					🚛
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:articulated_lorry:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tractor:">
					🚜
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tractor:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":kick_scooter:">
					🛴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:kick_scooter:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bike:">
					🚲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bike:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":motor_scooter:">
					🛵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:motor_scooter:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":motorcycle:">
					🏍
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:motorcycle:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rotating_light:">
					🚨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rotating_light:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":oncoming_police_car:">
					🚔
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:oncoming_police_car:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":oncoming_bus:">
					🚍
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:oncoming_bus:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":oncoming_automobile:">
					🚘
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:oncoming_automobile:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":oncoming_taxi:">
					🚖
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:oncoming_taxi:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":aerial_tramway:">
					🚡
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:aerial_tramway:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mountain_cableway:">
					🚠
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mountain_cableway:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":suspension_railway:">
					🚟
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:suspension_railway:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":railway_car:">
					🚃
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:railway_car:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":train:">
					🚋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:train:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mountain_railway:">
					🚞
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mountain_railway:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":monorail:">
					🚝
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:monorail:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bullettrain_side:">
					🚄
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bullettrain_side:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bullettrain_front:">
					🚅
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bullettrain_front:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":light_rail:">
					🚈
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:light_rail:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":steam_locomotive:">
					🚂
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:steam_locomotive:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":train2:">
					🚆
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:train2:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":metro:">
					🚇
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:metro:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tram:">
					🚊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tram:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":station:">
					🚉
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:station:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":helicopter:">
					🚁
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:helicopter:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":small_airplane:">
					🛩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:small_airplane:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":airplane:">
					✈
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:airplane:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":flight_departure:">
					🛫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:flight_departure:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":flight_arrival:">
					🛬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:flight_arrival:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rocket:">
					🚀
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rocket:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":artificial_satellite:">
					🛰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:artificial_satellite:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":seat:">
					💺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:seat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":canoe:">
					🛶
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:canoe:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":boat:">
					⛵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:boat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sailboat:">
					⛵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sailboat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":motor_boat:">
					🛥
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:motor_boat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":speedboat:">
					🚤
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:speedboat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":passenger_ship:">
					🛳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:passenger_ship:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ferry:">
					⛴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ferry:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ship:">
					🚢
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ship:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":anchor:">
					⚓
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:anchor:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":construction:">
					🚧
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:construction:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fuelpump:">
					⛽
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fuelpump:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":busstop:">
					🚏
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:busstop:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":vertical_traffic_light:">
					🚦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:vertical_traffic_light:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":traffic_light:">
					🚥
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:traffic_light:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":world_map:">
					🗺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:world_map:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":moyai:">
					🗿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:moyai:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":statue_of_liberty:">
					🗽
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:statue_of_liberty:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fountain:">
					⛲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fountain:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tokyo_tower:">
					🗼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tokyo_tower:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":european_castle:">
					🏰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:european_castle:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":japanese_castle:">
					🏯
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:japanese_castle:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":stadium:">
					🏟
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:stadium:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ferris_wheel:">
					🎡
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ferris_wheel:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":roller_coaster:">
					🎢
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:roller_coaster:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":carousel_horse:">
					🎠
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:carousel_horse:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":parasol_on_ground:">
					⛱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:parasol_on_ground:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":beach_umbrella:">
					🏖
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:beach_umbrella:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":desert_island:">
					🏝
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:desert_island:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mountain:">
					⛰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mountain:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mountain_snow:">
					🏔
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mountain_snow:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mount_fuji:">
					🗻
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mount_fuji:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":volcano:">
					🌋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:volcano:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":desert:">
					🏜
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:desert:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":camping:">
					🏕
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:camping:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tent:">
					⛺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tent:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":railway_track:">
					🛤
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:railway_track:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":motorway:">
					🛣
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:motorway:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":building_construction:">
					🏗
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:building_construction:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":factory:">
					🏭
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:factory:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":house:">
					🏠
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:house:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":house_with_garden:">
					🏡
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:house_with_garden:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":houses:">
					🏘
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:houses:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":derelict_house:">
					🏚
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:derelict_house:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":office:">
					🏢
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:office:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":department_store:">
					🏬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:department_store:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":post_office:">
					🏣
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:post_office:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":european_post_office:">
					🏤
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:european_post_office:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hospital:">
					🏥
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hospital:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hotel:">
					🏨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hotel:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":convenience_store:">
					🏪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:convenience_store:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":school:">
					🏫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:school:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":love_hotel:">
					🏩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:love_hotel:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":wedding:">
					💒
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:wedding:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":classical_building:">
					🏛
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:classical_building:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":church:">
					⛪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:church:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mosque:">
					🕌
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mosque:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":synagogue:">
					🕍
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:synagogue:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":kaaba:">
					🕋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:kaaba:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":shinto_shrine:">
					⛩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:shinto_shrine:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":japan:">
					🗾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:japan:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rice_scene:">
					🎑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rice_scene:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":national_park:">
					🏞
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:national_park:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sunrise:">
					🌅
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sunrise:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sunrise_over_mountains:">
					🌄
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sunrise_over_mountains:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":stars:">
					🌠
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:stars:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sparkler:">
					🎇
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sparkler:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fireworks:">
					🎆
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fireworks:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":city_sunrise:">
					🌇
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:city_sunrise:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":city_sunset:">
					🌆
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:city_sunset:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cityscape:">
					🏙
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cityscape:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":night_with_stars:">
					🌃
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:night_with_stars:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":milky_way:">
					🌌
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:milky_way:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bridge_at_night:">
					🌉
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bridge_at_night:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":foggy:">
					🌁
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:foggy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":watch:">
					⌚
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:watch:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":iphone:">
					📱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:iphone:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":calling:">
					📲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:calling:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":computer:">
					💻
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:computer:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":keyboard:">
					⌨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:keyboard:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":desktop_computer:">
					🖥
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:desktop_computer:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":printer:">
					🖨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:printer:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":computer_mouse:">
					🖱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:computer_mouse:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":trackball:">
					🖲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:trackball:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":joystick:">
					🕹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:joystick:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clamp:">
					🗜
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clamp:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":minidisc:">
					💽
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:minidisc:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":floppy_disk:">
					💾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:floppy_disk:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cd:">
					💿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cd:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dvd:">
					📀
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dvd:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":vhs:">
					📼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:vhs:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":camera:">
					📷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:camera:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":camera_flash:">
					📸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:camera_flash:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":video_camera:">
					📹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:video_camera:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":movie_camera:">
					🎥
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:movie_camera:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":film_projector:">
					📽
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:film_projector:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":film_strip:">
					🎞
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:film_strip:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":telephone_receiver:">
					📞
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:telephone_receiver:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":phone:">
					☎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:phone:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":telephone:">
					☎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:telephone:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pager:">
					📟
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pager:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fax:">
					📠
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fax:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tv:">
					📺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tv:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":radio:">
					📻
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:radio:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":studio_microphone:">
					🎙
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:studio_microphone:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":level_slider:">
					🎚
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:level_slider:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":control_knobs:">
					🎛
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:control_knobs:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":stopwatch:">
					⏱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:stopwatch:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":timer_clock:">
					⏲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:timer_clock:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":alarm_clock:">
					⏰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:alarm_clock:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mantelpiece_clock:">
					🕰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mantelpiece_clock:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hourglass:">
					⌛
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hourglass:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hourglass_flowing_sand:">
					⏳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hourglass_flowing_sand:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":satellite:">
					📡
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:satellite:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":battery:">
					🔋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:battery:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":electric_plug:">
					🔌
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:electric_plug:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bulb:">
					💡
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bulb:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":flashlight:">
					🔦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:flashlight:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":candle:">
					🕯
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:candle:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":wastebasket:">
					🗑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:wastebasket:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":oil_drum:">
					🛢
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:oil_drum:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":money_with_wings:">
					💸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:money_with_wings:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dollar:">
					💵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dollar:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":yen:">
					💴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:yen:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":euro:">
					💶
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:euro:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pound:">
					💷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pound:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":moneybag:">
					💰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:moneybag:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":credit_card:">
					💳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:credit_card:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":gem:">
					💎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:gem:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":balance_scale:">
					⚖
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:balance_scale:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":wrench:">
					🔧
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:wrench:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hammer:">
					🔨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hammer:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hammer_and_pick:">
					⚒
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hammer_and_pick:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hammer_and_wrench:">
					🛠
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hammer_and_wrench:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pick:">
					⛏
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pick:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":nut_and_bolt:">
					🔩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:nut_and_bolt:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":gear:">
					⚙
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:gear:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":chains:">
					⛓
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:chains:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":gun:">
					🔫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:gun:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bomb:">
					💣
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bomb:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hocho:">
					🔪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hocho:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":knife:">
					🔪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:knife:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dagger:">
					🗡
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dagger:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":crossed_swords:">
					⚔
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:crossed_swords:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":shield:">
					🛡
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:shield:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":smoking:">
					🚬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:smoking:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":coffin:">
					⚰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:coffin:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":funeral_urn:">
					⚱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:funeral_urn:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":amphora:">
					🏺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:amphora:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":crystal_ball:">
					🔮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:crystal_ball:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":prayer_beads:">
					📿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:prayer_beads:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":barber:">
					💈
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:barber:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":alembic:">
					⚗
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:alembic:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":telescope:">
					🔭
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:telescope:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":microscope:">
					🔬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:microscope:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hole:">
					🕳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hole:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pill:">
					💊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pill:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":syringe:">
					💉
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:syringe:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":thermometer:">
					🌡
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:thermometer:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":toilet:">
					🚽
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:toilet:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":potable_water:">
					🚰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:potable_water:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":shower:">
					🚿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:shower:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bathtub:">
					🛁
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bathtub:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bath:">
					🛀
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bath:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bellhop_bell:">
					🛎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bellhop_bell:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":key:">
					🔑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:key:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":old_key:">
					🗝
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:old_key:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":door:">
					🚪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:door:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":couch_and_lamp:">
					🛋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:couch_and_lamp:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bed:">
					🛏
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bed:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sleeping_bed:">
					🛌
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sleeping_bed:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":framed_picture:">
					🖼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:framed_picture:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":shopping:">
					🛍
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:shopping:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":shopping_cart:">
					🛒
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:shopping_cart:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":gift:">
					🎁
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:gift:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":balloon:">
					🎈
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:balloon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":flags:">
					🎏
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:flags:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ribbon:">
					🎀
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ribbon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":confetti_ball:">
					🎊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:confetti_ball:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tada:">
					🎉
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tada:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dolls:">
					🎎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dolls:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":izakaya_lantern:">
					🏮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:izakaya_lantern:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":lantern:">
					🏮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:lantern:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":wind_chime:">
					🎐
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:wind_chime:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":email:">
					✉
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:email:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":envelope:">
					✉
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:envelope:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":envelope_with_arrow:">
					📩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:envelope_with_arrow:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":incoming_envelope:">
					📨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:incoming_envelope:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":e-mail:">
					📧
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:e-mail:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":love_letter:">
					💌
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:love_letter:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":inbox_tray:">
					📥
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:inbox_tray:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":outbox_tray:">
					📤
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:outbox_tray:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":package:">
					📦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:package:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":label:">
					🏷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:label:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mailbox_closed:">
					📪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mailbox_closed:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mailbox:">
					📫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mailbox:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mailbox_with_mail:">
					📬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mailbox_with_mail:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mailbox_with_no_mail:">
					📭
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mailbox_with_no_mail:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":postbox:">
					📮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:postbox:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":postal_horn:">
					📯
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:postal_horn:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":scroll:">
					📜
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:scroll:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":page_with_curl:">
					📃
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:page_with_curl:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":page_facing_up:">
					📄
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:page_facing_up:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bookmark_tabs:">
					📑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bookmark_tabs:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bar_chart:">
					📊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bar_chart:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":chart_with_upwards_trend:">
					📈
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:chart_with_upwards_trend:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":chart_with_downwards_trend:">
					📉
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:chart_with_downwards_trend:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":spiral_notepad:">
					🗒
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:spiral_notepad:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":spiral_calendar:">
					🗓
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:spiral_calendar:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":calendar:">
					📆
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:calendar:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":date:">
					📅
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:date:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":card_index:">
					📇
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:card_index:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":card_file_box:">
					🗃
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:card_file_box:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ballot_box:">
					🗳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ballot_box:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":file_cabinet:">
					🗄
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:file_cabinet:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clipboard:">
					📋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clipboard:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":file_folder:">
					📁
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:file_folder:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":open_file_folder:">
					📂
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:open_file_folder:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":card_index_dividers:">
					🗂
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:card_index_dividers:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":newspaper_roll:">
					🗞
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:newspaper_roll:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":newspaper:">
					📰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:newspaper:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":notebook:">
					📓
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:notebook:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":notebook_with_decorative_cover:">
					📔
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:notebook_with_decorative_cover:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ledger:">
					📒
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ledger:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":closed_book:">
					📕
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:closed_book:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":green_book:">
					📗
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:green_book:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":blue_book:">
					📘
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:blue_book:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":orange_book:">
					📙
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:orange_book:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":books:">
					📚
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:books:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":book:">
					📖
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:book:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":open_book:">
					📖
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:open_book:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bookmark:">
					🔖
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bookmark:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":link:">
					🔗
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:link:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":paperclip:">
					📎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:paperclip:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":paperclips:">
					🖇
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:paperclips:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":triangular_ruler:">
					📐
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:triangular_ruler:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":straight_ruler:">
					📏
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:straight_ruler:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pushpin:">
					📌
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pushpin:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":round_pushpin:">
					📍
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:round_pushpin:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":scissors:">
					✂
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:scissors:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pen:">
					🖊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pen:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fountain_pen:">
					🖋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fountain_pen:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":black_nib:">
					✒
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:black_nib:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":paintbrush:">
					🖌
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:paintbrush:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":crayon:">
					🖍
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:crayon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":memo:">
					📝
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:memo:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pencil:">
					📝
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pencil:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pencil2:">
					✏
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pencil2:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mag:">
					🔍
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mag:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mag_right:">
					🔎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mag_right:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":lock_with_ink_pen:">
					🔏
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:lock_with_ink_pen:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":closed_lock_with_key:">
					🔐
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:closed_lock_with_key:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":lock:">
					🔒
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:lock:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":unlock:">
					🔓
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:unlock:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":heart:">
					❤
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:heart:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":yellow_heart:">
					💛
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:yellow_heart:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":green_heart:">
					💚
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:green_heart:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":blue_heart:">
					💙
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:blue_heart:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":purple_heart:">
					💜
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:purple_heart:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":black_heart:">
					🖤
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:black_heart:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":broken_heart:">
					💔
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:broken_heart:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":heavy_heart_exclamation:">
					❣
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:heavy_heart_exclamation:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":two_hearts:">
					💕
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:two_hearts:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":revolving_hearts:">
					💞
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:revolving_hearts:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":heartbeat:">
					💓
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:heartbeat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":heartpulse:">
					💗
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:heartpulse:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sparkling_heart:">
					💖
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sparkling_heart:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cupid:">
					💘
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cupid:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":gift_heart:">
					💝
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:gift_heart:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":heart_decoration:">
					💟
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:heart_decoration:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":peace_symbol:">
					☮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:peace_symbol:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":latin_cross:">
					✝
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:latin_cross:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":star_and_crescent:">
					☪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:star_and_crescent:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":om:">
					🕉
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:om:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":wheel_of_dharma:">
					☸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:wheel_of_dharma:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":star_of_david:">
					✡
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:star_of_david:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":six_pointed_star:">
					🔯
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:six_pointed_star:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":menorah:">
					🕎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:menorah:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":yin_yang:">
					☯
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:yin_yang:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":orthodox_cross:">
					☦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:orthodox_cross:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":place_of_worship:">
					🛐
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:place_of_worship:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ophiuchus:">
					⛎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ophiuchus:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":aries:">
					♈
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:aries:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":taurus:">
					♉
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:taurus:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":gemini:">
					♊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:gemini:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cancer:">
					♋
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cancer:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":leo:">
					♌
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:leo:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":virgo:">
					♍
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:virgo:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":libra:">
					♎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:libra:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":scorpius:">
					♏
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:scorpius:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sagittarius:">
					♐
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sagittarius:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":capricorn:">
					♑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:capricorn:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":aquarius:">
					♒
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:aquarius:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pisces:">
					♓
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pisces:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":id:">
					🆔
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:id:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":atom_symbol:">
					⚛
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:atom_symbol:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":accept:">
					🉑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:accept:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":radioactive:">
					☢
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:radioactive:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":biohazard:">
					☣
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:biohazard:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mobile_phone_off:">
					📴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mobile_phone_off:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":vibration_mode:">
					📳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:vibration_mode:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":u6709:">
					🈶
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:u6709:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":u7121:">
					🈚
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:u7121:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":u7533:">
					🈸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:u7533:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":u55b6:">
					🈺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:u55b6:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":u6708:">
					🈷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:u6708:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":eight_pointed_black_star:">
					✴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:eight_pointed_black_star:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":vs:">
					🆚
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:vs:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":white_flower:">
					💮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:white_flower:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ideograph_advantage:">
					🉐
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ideograph_advantage:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":secret:">
					㊙
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:secret:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":congratulations:">
					㊗
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:congratulations:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":u5408:">
					🈴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:u5408:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":u6e80:">
					🈵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:u6e80:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":u5272:">
					🈹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:u5272:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":u7981:">
					🈲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:u7981:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":a:">
					🅰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:a:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":b:">
					🅱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:b:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ab:">
					🆎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ab:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cl:">
					🆑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cl:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":o2:">
					🅾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:o2:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sos:">
					🆘
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sos:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":x:">
					❌
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:x:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":o:">
					⭕
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:o:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":stop_sign:">
					🛑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:stop_sign:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":no_entry:">
					⛔
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:no_entry:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":name_badge:">
					📛
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:name_badge:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":no_entry_sign:">
					🚫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:no_entry_sign:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":100:">
					💯
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:100:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":anger:">
					💢
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:anger:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hotsprings:">
					♨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hotsprings:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":no_pedestrians:">
					🚷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:no_pedestrians:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":do_not_litter:">
					🚯
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:do_not_litter:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":no_bicycles:">
					🚳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:no_bicycles:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":non-potable_water:">
					🚱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:non-potable_water:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":underage:">
					🔞
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:underage:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":no_mobile_phones:">
					📵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:no_mobile_phones:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":no_smoking:">
					🚭
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:no_smoking:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":exclamation:">
					❗
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:exclamation:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":heavy_exclamation_mark:">
					❗
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:heavy_exclamation_mark:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":grey_exclamation:">
					❕
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:grey_exclamation:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":question:">
					❓
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:question:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":grey_question:">
					❔
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:grey_question:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bangbang:">
					‼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bangbang:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":interrobang:">
					⁉
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:interrobang:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":low_brightness:">
					🔅
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:low_brightness:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":high_brightness:">
					🔆
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:high_brightness:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":part_alternation_mark:">
					〽
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:part_alternation_mark:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":warning:">
					⚠
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:warning:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":children_crossing:">
					🚸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:children_crossing:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":trident:">
					🔱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:trident:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fleur_de_lis:">
					⚜
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fleur_de_lis:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":beginner:">
					🔰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:beginner:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":recycle:">
					♻
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:recycle:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":white_check_mark:">
					✅
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:white_check_mark:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":u6307:">
					🈯
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:u6307:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":chart:">
					💹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:chart:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sparkle:">
					❇
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sparkle:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":eight_spoked_asterisk:">
					✳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:eight_spoked_asterisk:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":negative_squared_cross_mark:">
					❎
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:negative_squared_cross_mark:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":globe_with_meridians:">
					🌐
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:globe_with_meridians:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":diamond_shape_with_a_dot_inside:">
					💠
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:diamond_shape_with_a_dot_inside:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":m:">
					Ⓜ
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:m:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cyclone:">
					🌀
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cyclone:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":zzz:">
					💤
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:zzz:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":atm:">
					🏧
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:atm:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":wc:">
					🚾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:wc:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":wheelchair:">
					♿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:wheelchair:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":parking:">
					🅿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:parking:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":u7a7a:">
					🈳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:u7a7a:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sa:">
					🈂
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sa:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":passport_control:">
					🛂
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:passport_control:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":customs:">
					🛃
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:customs:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":baggage_claim:">
					🛄
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:baggage_claim:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":left_luggage:">
					🛅
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:left_luggage:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mens:">
					🚹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mens:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":womens:">
					🚺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:womens:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":baby_symbol:">
					🚼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:baby_symbol:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":restroom:">
					🚻
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:restroom:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":put_litter_in_its_place:">
					🚮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:put_litter_in_its_place:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cinema:">
					🎦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cinema:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":signal_strength:">
					📶
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:signal_strength:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":koko:">
					🈁
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:koko:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":symbols:">
					🔣
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:symbols:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":information_source:">
					ℹ
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:information_source:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":abc:">
					🔤
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:abc:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":abcd:">
					🔡
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:abcd:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":capital_abcd:">
					🔠
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:capital_abcd:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ng:">
					🆖
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ng:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ok:">
					🆗
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ok:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":up:">
					🆙
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:up:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cool:">
					🆒
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cool:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":new:">
					🆕
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:new:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":free:">
					🆓
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:free:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":zero:">
					0
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:zero:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":one:">
					1
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:one:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":two:">
					2
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:two:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":three:">
					3
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:three:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":four:">
					4
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:four:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":five:">
					5
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:five:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":six:">
					6
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:six:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":seven:">
					7
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:seven:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":eight:">
					8
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:eight:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":nine:">
					9
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:nine:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":keycap_ten:">
					🔟
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:keycap_ten:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":1234:">
					🔢
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:1234:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hash:">
					#
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hash:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":asterisk:">
					*
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:asterisk:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":arrow_forward:">
					▶
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:arrow_forward:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pause_button:">
					⏸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pause_button:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":play_or_pause_button:">
					⏯
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:play_or_pause_button:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":stop_button:">
					⏹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:stop_button:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":record_button:">
					⏺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:record_button:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":next_track_button:">
					⏭
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:next_track_button:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":previous_track_button:">
					⏮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:previous_track_button:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fast_forward:">
					⏩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fast_forward:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rewind:">
					⏪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rewind:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":arrow_double_up:">
					⏫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:arrow_double_up:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":arrow_double_down:">
					⏬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:arrow_double_down:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":arrow_backward:">
					◀
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:arrow_backward:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":arrow_up_small:">
					🔼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:arrow_up_small:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":arrow_down_small:">
					🔽
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:arrow_down_small:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":arrow_right:">
					➡
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:arrow_right:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":arrow_left:">
					⬅
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:arrow_left:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":arrow_up:">
					⬆
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:arrow_up:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":arrow_down:">
					⬇
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:arrow_down:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":arrow_upper_right:">
					↗
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:arrow_upper_right:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":arrow_lower_right:">
					↘
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:arrow_lower_right:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":arrow_lower_left:">
					↙
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:arrow_lower_left:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":arrow_upper_left:">
					↖
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:arrow_upper_left:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":arrow_up_down:">
					↕
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:arrow_up_down:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":left_right_arrow:">
					↔
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:left_right_arrow:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":arrow_right_hook:">
					↪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:arrow_right_hook:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":leftwards_arrow_with_hook:">
					↩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:leftwards_arrow_with_hook:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":arrow_heading_up:">
					⤴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:arrow_heading_up:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":arrow_heading_down:">
					⤵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:arrow_heading_down:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":twisted_rightwards_arrows:">
					🔀
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:twisted_rightwards_arrows:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":repeat:">
					🔁
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:repeat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":repeat_one:">
					🔂
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:repeat_one:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":arrows_counterclockwise:">
					🔄
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:arrows_counterclockwise:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":arrows_clockwise:">
					🔃
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:arrows_clockwise:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":musical_note:">
					🎵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:musical_note:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":notes:">
					🎶
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:notes:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":heavy_plus_sign:">
					➕
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:heavy_plus_sign:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":heavy_minus_sign:">
					➖
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:heavy_minus_sign:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":heavy_division_sign:">
					➗
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:heavy_division_sign:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":heavy_multiplication_x:">
					✖
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:heavy_multiplication_x:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":heavy_dollar_sign:">
					💲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:heavy_dollar_sign:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":currency_exchange:">
					💱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:currency_exchange:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tm:">
					™
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tm:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":copyright:">
					©
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:copyright:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":registered:">
					®
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:registered:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":wavy_dash:">
					〰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:wavy_dash:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":curly_loop:">
					➰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:curly_loop:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":loop:">
					➿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:loop:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":end:">
					🔚
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:end:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":back:">
					🔙
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:back:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":on:">
					🔛
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:on:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":top:">
					🔝
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:top:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":soon:">
					🔜
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:soon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":heavy_check_mark:">
					✔
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:heavy_check_mark:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ballot_box_with_check:">
					☑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ballot_box_with_check:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":radio_button:">
					🔘
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:radio_button:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":white_circle:">
					⚪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:white_circle:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":black_circle:">
					⚫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:black_circle:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":red_circle:">
					🔴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:red_circle:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":large_blue_circle:">
					🔵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:large_blue_circle:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":small_red_triangle:">
					🔺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:small_red_triangle:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":small_red_triangle_down:">
					🔻
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:small_red_triangle_down:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":small_orange_diamond:">
					🔸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:small_orange_diamond:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":small_blue_diamond:">
					🔹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:small_blue_diamond:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":large_orange_diamond:">
					🔶
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:large_orange_diamond:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":large_blue_diamond:">
					🔷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:large_blue_diamond:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":white_square_button:">
					🔳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:white_square_button:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":black_square_button:">
					🔲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:black_square_button:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":black_small_square:">
					▪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:black_small_square:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":white_small_square:">
					▫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:white_small_square:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":black_medium_small_square:">
					◾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:black_medium_small_square:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":white_medium_small_square:">
					◽
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:white_medium_small_square:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":black_medium_square:">
					◼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:black_medium_square:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":white_medium_square:">
					◻
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:white_medium_square:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":black_large_square:">
					⬛
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:black_large_square:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":white_large_square:">
					⬜
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:white_large_square:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":speaker:">
					🔈
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:speaker:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mute:">
					🔇
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mute:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sound:">
					🔉
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sound:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":loud_sound:">
					🔊
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:loud_sound:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bell:">
					🔔
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bell:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":no_bell:">
					🔕
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:no_bell:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mega:">
					📣
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mega:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":loudspeaker:">
					📢
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:loudspeaker:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":eye_speech_bubble:">
					👁
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:eye_speech_bubble:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":speech_balloon:">
					💬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:speech_balloon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":thought_balloon:">
					💭
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:thought_balloon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":right_anger_bubble:">
					🗯
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:right_anger_bubble:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":spades:">
					♠
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:spades:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clubs:">
					♣
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clubs:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hearts:">
					♥
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hearts:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":diamonds:">
					♦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:diamonds:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":black_joker:">
					🃏
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:black_joker:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":flower_playing_cards:">
					🎴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:flower_playing_cards:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mahjong:">
					🀄
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mahjong:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock1:">
					🕐
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock1:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock2:">
					🕑
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock2:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock3:">
					🕒
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock3:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock4:">
					🕓
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock4:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock5:">
					🕔
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock5:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock6:">
					🕕
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock6:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock7:">
					🕖
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock7:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock8:">
					🕗
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock8:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock9:">
					🕘
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock9:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock10:">
					🕙
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock10:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock11:">
					🕚
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock11:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock12:">
					🕛
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock12:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock130:">
					🕜
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock130:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock230:">
					🕝
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock230:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock330:">
					🕞
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock330:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock430:">
					🕟
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock430:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock530:">
					🕠
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock530:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock630:">
					🕡
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock630:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock730:">
					🕢
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock730:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock830:">
					🕣
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock830:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock930:">
					🕤
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock930:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock1030:">
					🕥
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock1030:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock1130:">
					🕦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock1130:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":clock1230:">
					🕧
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:clock1230:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":white_flag:">
					🏳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:white_flag:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":black_flag:">
					🏴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:black_flag:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":checkered_flag:">
					🏁
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:checkered_flag:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":triangular_flag_on_post:">
					🚩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:triangular_flag_on_post:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rainbow_flag:">
					🏳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rainbow_flag:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":afghanistan:">
					🇦🇫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:afghanistan:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":aland_islands:">
					🇦🇽
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:aland_islands:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":albania:">
					🇦🇱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:albania:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":algeria:">
					🇩🇿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:algeria:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":american_samoa:">
					🇦🇸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:american_samoa:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":andorra:">
					🇦🇩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:andorra:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":angola:">
					🇦🇴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:angola:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":anguilla:">
					🇦🇮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:anguilla:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":antarctica:">
					🇦🇶
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:antarctica:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":antigua_barbuda:">
					🇦🇬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:antigua_barbuda:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":argentina:">
					🇦🇷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:argentina:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":armenia:">
					🇦🇲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:armenia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":aruba:">
					🇦🇼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:aruba:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":australia:">
					🇦🇺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:australia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":austria:">
					🇦🇹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:austria:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":azerbaijan:">
					🇦🇿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:azerbaijan:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bahamas:">
					🇧🇸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bahamas:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bahrain:">
					🇧🇭
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bahrain:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bangladesh:">
					🇧🇩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bangladesh:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":barbados:">
					🇧🇧
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:barbados:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":belarus:">
					🇧🇾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:belarus:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":belgium:">
					🇧🇪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:belgium:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":belize:">
					🇧🇿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:belize:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":benin:">
					🇧🇯
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:benin:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bermuda:">
					🇧🇲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bermuda:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bhutan:">
					🇧🇹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bhutan:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bolivia:">
					🇧🇴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bolivia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":caribbean_netherlands:">
					🇧🇶
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:caribbean_netherlands:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bosnia_herzegovina:">
					🇧🇦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bosnia_herzegovina:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":botswana:">
					🇧🇼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:botswana:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":brazil:">
					🇧🇷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:brazil:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":british_indian_ocean_territory:">
					🇮🇴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:british_indian_ocean_territory:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":british_virgin_islands:">
					🇻🇬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:british_virgin_islands:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":brunei:">
					🇧🇳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:brunei:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":bulgaria:">
					🇧🇬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:bulgaria:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":burkina_faso:">
					🇧🇫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:burkina_faso:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":burundi:">
					🇧🇮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:burundi:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cape_verde:">
					🇨🇻
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cape_verde:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cambodia:">
					🇰🇭
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cambodia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cameroon:">
					🇨🇲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cameroon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":canada:">
					🇨🇦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:canada:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":canary_islands:">
					🇮🇨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:canary_islands:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cayman_islands:">
					🇰🇾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cayman_islands:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":central_african_republic:">
					🇨🇫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:central_african_republic:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":chad:">
					🇹🇩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:chad:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":chile:">
					🇨🇱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:chile:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cn:">
					🇨🇳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cn:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":christmas_island:">
					🇨🇽
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:christmas_island:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cocos_islands:">
					🇨🇨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cocos_islands:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":colombia:">
					🇨🇴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:colombia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":comoros:">
					🇰🇲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:comoros:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":congo_brazzaville:">
					🇨🇬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:congo_brazzaville:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":congo_kinshasa:">
					🇨🇩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:congo_kinshasa:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cook_islands:">
					🇨🇰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cook_islands:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":costa_rica:">
					🇨🇷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:costa_rica:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cote_divoire:">
					🇨🇮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cote_divoire:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":croatia:">
					🇭🇷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:croatia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cuba:">
					🇨🇺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cuba:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":curacao:">
					🇨🇼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:curacao:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":cyprus:">
					🇨🇾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:cyprus:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":czech_republic:">
					🇨🇿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:czech_republic:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":denmark:">
					🇩🇰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:denmark:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":djibouti:">
					🇩🇯
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:djibouti:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dominica:">
					🇩🇲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dominica:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":dominican_republic:">
					🇩🇴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:dominican_republic:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ecuador:">
					🇪🇨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ecuador:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":egypt:">
					🇪🇬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:egypt:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":el_salvador:">
					🇸🇻
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:el_salvador:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":equatorial_guinea:">
					🇬🇶
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:equatorial_guinea:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":eritrea:">
					🇪🇷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:eritrea:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":estonia:">
					🇪🇪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:estonia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ethiopia:">
					🇪🇹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ethiopia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":eu:">
					🇪🇺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:eu:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":european_union:">
					🇪🇺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:european_union:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":falkland_islands:">
					🇫🇰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:falkland_islands:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":faroe_islands:">
					🇫🇴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:faroe_islands:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fiji:">
					🇫🇯
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fiji:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":finland:">
					🇫🇮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:finland:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":fr:">
					🇫🇷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:fr:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":french_guiana:">
					🇬🇫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:french_guiana:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":french_polynesia:">
					🇵🇫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:french_polynesia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":french_southern_territories:">
					🇹🇫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:french_southern_territories:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":gabon:">
					🇬🇦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:gabon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":gambia:">
					🇬🇲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:gambia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":georgia:">
					🇬🇪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:georgia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":de:">
					🇩🇪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:de:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ghana:">
					🇬🇭
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ghana:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":gibraltar:">
					🇬🇮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:gibraltar:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":greece:">
					🇬🇷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:greece:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":greenland:">
					🇬🇱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:greenland:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":grenada:">
					🇬🇩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:grenada:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":guadeloupe:">
					🇬🇵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:guadeloupe:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":guam:">
					🇬🇺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:guam:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":guatemala:">
					🇬🇹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:guatemala:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":guernsey:">
					🇬🇬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:guernsey:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":guinea:">
					🇬🇳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:guinea:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":guinea_bissau:">
					🇬🇼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:guinea_bissau:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":guyana:">
					🇬🇾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:guyana:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":haiti:">
					🇭🇹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:haiti:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":honduras:">
					🇭🇳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:honduras:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hong_kong:">
					🇭🇰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hong_kong:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":hungary:">
					🇭🇺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:hungary:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":iceland:">
					🇮🇸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:iceland:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":india:">
					🇮🇳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:india:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":indonesia:">
					🇮🇩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:indonesia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":iran:">
					🇮🇷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:iran:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":iraq:">
					🇮🇶
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:iraq:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ireland:">
					🇮🇪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ireland:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":isle_of_man:">
					🇮🇲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:isle_of_man:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":israel:">
					🇮🇱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:israel:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":it:">
					🇮🇹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:it:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":jamaica:">
					🇯🇲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:jamaica:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":jp:">
					🇯🇵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:jp:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":crossed_flags:">
					🎌
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:crossed_flags:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":jersey:">
					🇯🇪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:jersey:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":jordan:">
					🇯🇴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:jordan:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":kazakhstan:">
					🇰🇿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:kazakhstan:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":kenya:">
					🇰🇪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:kenya:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":kiribati:">
					🇰🇮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:kiribati:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":kosovo:">
					🇽🇰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:kosovo:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":kuwait:">
					🇰🇼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:kuwait:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":kyrgyzstan:">
					🇰🇬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:kyrgyzstan:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":laos:">
					🇱🇦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:laos:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":latvia:">
					🇱🇻
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:latvia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":lebanon:">
					🇱🇧
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:lebanon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":lesotho:">
					🇱🇸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:lesotho:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":liberia:">
					🇱🇷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:liberia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":libya:">
					🇱🇾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:libya:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":liechtenstein:">
					🇱🇮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:liechtenstein:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":lithuania:">
					🇱🇹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:lithuania:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":luxembourg:">
					🇱🇺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:luxembourg:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":macau:">
					🇲🇴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:macau:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":macedonia:">
					🇲🇰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:macedonia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":madagascar:">
					🇲🇬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:madagascar:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":malawi:">
					🇲🇼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:malawi:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":malaysia:">
					🇲🇾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:malaysia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":maldives:">
					🇲🇻
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:maldives:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mali:">
					🇲🇱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mali:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":malta:">
					🇲🇹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:malta:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":marshall_islands:">
					🇲🇭
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:marshall_islands:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":martinique:">
					🇲🇶
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:martinique:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mauritania:">
					🇲🇷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mauritania:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mauritius:">
					🇲🇺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mauritius:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mayotte:">
					🇾🇹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mayotte:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mexico:">
					🇲🇽
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mexico:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":micronesia:">
					🇫🇲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:micronesia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":moldova:">
					🇲🇩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:moldova:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":monaco:">
					🇲🇨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:monaco:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mongolia:">
					🇲🇳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mongolia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":montenegro:">
					🇲🇪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:montenegro:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":montserrat:">
					🇲🇸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:montserrat:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":morocco:">
					🇲🇦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:morocco:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":mozambique:">
					🇲🇿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:mozambique:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":myanmar:">
					🇲🇲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:myanmar:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":namibia:">
					🇳🇦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:namibia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":nauru:">
					🇳🇷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:nauru:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":nepal:">
					🇳🇵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:nepal:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":netherlands:">
					🇳🇱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:netherlands:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":new_caledonia:">
					🇳🇨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:new_caledonia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":new_zealand:">
					🇳🇿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:new_zealand:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":nicaragua:">
					🇳🇮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:nicaragua:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":niger:">
					🇳🇪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:niger:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":nigeria:">
					🇳🇬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:nigeria:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":niue:">
					🇳🇺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:niue:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":norfolk_island:">
					🇳🇫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:norfolk_island:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":northern_mariana_islands:">
					🇲🇵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:northern_mariana_islands:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":north_korea:">
					🇰🇵
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:north_korea:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":norway:">
					🇳🇴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:norway:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":oman:">
					🇴🇲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:oman:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pakistan:">
					🇵🇰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pakistan:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":palau:">
					🇵🇼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:palau:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":palestinian_territories:">
					🇵🇸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:palestinian_territories:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":panama:">
					🇵🇦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:panama:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":papua_new_guinea:">
					🇵🇬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:papua_new_guinea:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":paraguay:">
					🇵🇾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:paraguay:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":peru:">
					🇵🇪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:peru:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":philippines:">
					🇵🇭
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:philippines:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":pitcairn_islands:">
					🇵🇳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:pitcairn_islands:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":poland:">
					🇵🇱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:poland:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":portugal:">
					🇵🇹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:portugal:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":puerto_rico:">
					🇵🇷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:puerto_rico:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":qatar:">
					🇶🇦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:qatar:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":reunion:">
					🇷🇪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:reunion:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":romania:">
					🇷🇴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:romania:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ru:">
					🇷🇺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ru:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":rwanda:">
					🇷🇼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:rwanda:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":st_barthelemy:">
					🇧🇱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:st_barthelemy:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":st_helena:">
					🇸🇭
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:st_helena:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":st_kitts_nevis:">
					🇰🇳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:st_kitts_nevis:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":st_lucia:">
					🇱🇨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:st_lucia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":st_pierre_miquelon:">
					🇵🇲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:st_pierre_miquelon:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":st_vincent_grenadines:">
					🇻🇨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:st_vincent_grenadines:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":samoa:">
					🇼🇸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:samoa:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":san_marino:">
					🇸🇲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:san_marino:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sao_tome_principe:">
					🇸🇹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sao_tome_principe:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":saudi_arabia:">
					🇸🇦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:saudi_arabia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":senegal:">
					🇸🇳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:senegal:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":serbia:">
					🇷🇸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:serbia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":seychelles:">
					🇸🇨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:seychelles:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sierra_leone:">
					🇸🇱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sierra_leone:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":singapore:">
					🇸🇬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:singapore:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sint_maarten:">
					🇸🇽
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sint_maarten:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":slovakia:">
					🇸🇰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:slovakia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":slovenia:">
					🇸🇮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:slovenia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":solomon_islands:">
					🇸🇧
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:solomon_islands:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":somalia:">
					🇸🇴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:somalia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":south_africa:">
					🇿🇦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:south_africa:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":south_georgia_south_sandwich_islands:">
					🇬🇸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:south_georgia_south_sandwich_islands:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":kr:">
					🇰🇷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:kr:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":south_sudan:">
					🇸🇸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:south_sudan:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":es:">
					🇪🇸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:es:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sri_lanka:">
					🇱🇰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sri_lanka:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sudan:">
					🇸🇩
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sudan:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":suriname:">
					🇸🇷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:suriname:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":swaziland:">
					🇸🇿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:swaziland:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":sweden:">
					🇸🇪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:sweden:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":switzerland:">
					🇨🇭
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:switzerland:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":syria:">
					🇸🇾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:syria:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":taiwan:">
					🇹🇼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:taiwan:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tajikistan:">
					🇹🇯
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tajikistan:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tanzania:">
					🇹🇿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tanzania:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":thailand:">
					🇹🇭
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:thailand:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":timor_leste:">
					🇹🇱
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:timor_leste:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":togo:">
					🇹🇬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:togo:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tokelau:">
					🇹🇰
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tokelau:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tonga:">
					🇹🇴
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tonga:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":trinidad_tobago:">
					🇹🇹
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:trinidad_tobago:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tunisia:">
					🇹🇳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tunisia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tr:">
					🇹🇷
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tr:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":turkmenistan:">
					🇹🇲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:turkmenistan:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":turks_caicos_islands:">
					🇹🇨
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:turks_caicos_islands:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":tuvalu:">
					🇹🇻
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:tuvalu:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":uganda:">
					🇺🇬
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:uganda:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":ukraine:">
					🇺🇦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:ukraine:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":united_arab_emirates:">
					🇦🇪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:united_arab_emirates:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":gb:">
					🇬🇧
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:gb:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":uk:">
					🇬🇧
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:uk:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":us:">
					🇺🇸
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:us:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":us_virgin_islands:">
					🇻🇮
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:us_virgin_islands:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":uruguay:">
					🇺🇾
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:uruguay:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":uzbekistan:">
					🇺🇿
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:uzbekistan:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":vanuatu:">
					🇻🇺
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:vanuatu:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":vatican_city:">
					🇻🇦
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:vatican_city:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":venezuela:">
					🇻🇪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:venezuela:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":vietnam:">
					🇻🇳
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:vietnam:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":wallis_futuna:">
					🇼🇫
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:wallis_futuna:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":western_sahara:">
					🇪🇭
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:western_sahara:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":yemen:">
					🇾🇪
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:yemen:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":zambia:">
					🇿🇲
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:zambia:</span>
				<span class="tooltipped raw-emoji" style="width: 2em;display: inline-block;text-align: center;" data-delay="50" data-position="bottom" data-tooltip=":zimbabwe:">
					🇿🇼
				</span>
				<span style="color: rgba(0,0,0,0);font-size: 0;">:zimbabwe:</span>

				</div>
			</div>
<?php
require_once Values::FOOTER_INC;
 
 
