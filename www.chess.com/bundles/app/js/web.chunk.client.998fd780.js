window.chesscom=window.chesscom||{},window.chesscom.routes=window.chesscom.routes||{},Object.assign(window.chesscom.routes,{web_member_callback_trophy_list:{tokens:[["variable","/","[^/]++","trophyType"],["variable","/","[^/]++","username"],["text","/callback/member/trophy"]],defaults:{trophyType:null,subdomain:"www"},requirements:{subdomain:"www|schach|ru|uk|ajedrez|fr|scacchi|sakk|schaken|szachy",_locale:"af_ZA|ar_AR|az_AZ|be_BY|bg_BG|bn_BD|bs_BA|ca|cs_CZ|da_DK|de_DE|el_GR|en_US|es_ES|et_EE|eu_ES|fa_IR|fi_FI|fil_PH|fr_FR|gl_ES|he_IL|hi_IN|hr_HR|hu_HU|hy_AM|id_ID|is_IS|it_IT|ja_JP|ka_GE|ko_KR|lt_LT|lv_LV|nl_BE|ms_MY|nl_NL|no_NO|pl_PL|pt_BR|pt_PT|ro_RO|ru_RU|sk_SK|sl_SI|sq_AL|sr_RS|sv_SE|tk_TM|tr_TR|uk_UA|ur_PK|vi_VN|zh_CN|zh_HK|zh_TW"},hosttokens:[["text",".chess.com"],["variable","","www|schach|ru|uk|ajedrez|fr|scacchi|sakk|schaken|szachy","subdomain"]],methods:[],schemes:["https"]},web_callback_get_trophies:{tokens:[["text","/callback/trophies"]],defaults:{subdomain:"www"},requirements:{subdomain:"www|schach|ru|uk|ajedrez|fr|scacchi|sakk|schaken|szachy",_locale:"af_ZA|ar_AR|az_AZ|be_BY|bg_BG|bn_BD|bs_BA|ca|cs_CZ|da_DK|de_DE|el_GR|en_US|es_ES|et_EE|eu_ES|fa_IR|fi_FI|fil_PH|fr_FR|gl_ES|he_IL|hi_IN|hr_HR|hu_HU|hy_AM|id_ID|is_IS|it_IT|ja_JP|ka_GE|ko_KR|lt_LT|lv_LV|nl_BE|ms_MY|nl_NL|no_NO|pl_PL|pt_BR|pt_PT|ro_RO|ru_RU|sk_SK|sl_SI|sq_AL|sr_RS|sv_SE|tk_TM|tr_TR|uk_UA|ur_PK|vi_VN|zh_CN|zh_HK|zh_TW"},hosttokens:[["text",".chess.com"],["variable","","www|schach|ru|uk|ajedrez|fr|scacchi|sakk|schaken|szachy","subdomain"]],methods:[],schemes:["https"]},web_user_trophy_showcase_callback:{tokens:[["text","/showcase"],["variable","/","[^/]++","username"],["text","/callback/user/trophy"]],defaults:{subdomain:"www"},requirements:{subdomain:"www|schach|ru|uk|ajedrez|fr|scacchi|sakk|schaken|szachy",_locale:"af_ZA|ar_AR|az_AZ|be_BY|bg_BG|bn_BD|bs_BA|ca|cs_CZ|da_DK|de_DE|el_GR|en_US|es_ES|et_EE|eu_ES|fa_IR|fi_FI|fil_PH|fr_FR|gl_ES|he_IL|hi_IN|hr_HR|hu_HU|hy_AM|id_ID|is_IS|it_IT|ja_JP|ka_GE|ko_KR|lt_LT|lv_LV|nl_BE|ms_MY|nl_NL|no_NO|pl_PL|pt_BR|pt_PT|ro_RO|ru_RU|sk_SK|sl_SI|sq_AL|sr_RS|sv_SE|tk_TM|tr_TR|uk_UA|ur_PK|vi_VN|zh_CN|zh_HK|zh_TW"},hosttokens:[["text",".chess.com"],["variable","","www|schach|ru|uk|ajedrez|fr|scacchi|sakk|schaken|szachy","subdomain"]],methods:[],schemes:["https"]},web_callback_count_user_trophies:{tokens:[["variable","/","[^/]++","username"],["text","/callback/count/user_trophy"]],defaults:{subdomain:"www"},requirements:{subdomain:"www|schach|ru|uk|ajedrez|fr|scacchi|sakk|schaken|szachy",_locale:"af_ZA|ar_AR|az_AZ|be_BY|bg_BG|bn_BD|bs_BA|ca|cs_CZ|da_DK|de_DE|el_GR|en_US|es_ES|et_EE|eu_ES|fa_IR|fi_FI|fil_PH|fr_FR|gl_ES|he_IL|hi_IN|hr_HR|hu_HU|hy_AM|id_ID|is_IS|it_IT|ja_JP|ka_GE|ko_KR|lt_LT|lv_LV|nl_BE|ms_MY|nl_NL|no_NO|pl_PL|pt_BR|pt_PT|ro_RO|ru_RU|sk_SK|sl_SI|sq_AL|sr_RS|sv_SE|tk_TM|tr_TR|uk_UA|ur_PK|vi_VN|zh_CN|zh_HK|zh_TW"},hosttokens:[["text",".chess.com"],["variable","","www|schach|ru|uk|ajedrez|fr|scacchi|sakk|schaken|szachy","subdomain"]],methods:[],schemes:["https"]},web_feature_trophy_callback:{tokens:[["variable","/","0|1","featured"],["variable","/","\\d+","id"],["text","/callback/feature/trophy"]],defaults:{subdomain:"www"},requirements:{id:"\\d+",featured:"0|1",subdomain:"www|schach|ru|uk|ajedrez|fr|scacchi|sakk|schaken|szachy",_locale:"af_ZA|ar_AR|az_AZ|be_BY|bg_BG|bn_BD|bs_BA|ca|cs_CZ|da_DK|de_DE|el_GR|en_US|es_ES|et_EE|eu_ES|fa_IR|fi_FI|fil_PH|fr_FR|gl_ES|he_IL|hi_IN|hr_HR|hu_HU|hy_AM|id_ID|is_IS|it_IT|ja_JP|ka_GE|ko_KR|lt_LT|lv_LV|nl_BE|ms_MY|nl_NL|no_NO|pl_PL|pt_BR|pt_PT|ro_RO|ru_RU|sk_SK|sl_SI|sq_AL|sr_RS|sv_SE|tk_TM|tr_TR|uk_UA|ur_PK|vi_VN|zh_CN|zh_HK|zh_TW"},hosttokens:[["text",".chess.com"],["variable","","www|schach|ru|uk|ajedrez|fr|scacchi|sakk|schaken|szachy","subdomain"]],methods:["POST"],schemes:["https"]},web_award_trophy_callback:{tokens:[["variable","/","\\d+","trophyId"],["variable","/","[^/]++","username"],["text","/callback/award/trophy"]],defaults:{subdomain:"www"},requirements:{trophyId:"\\d+",subdomain:"www|schach|ru|uk|ajedrez|fr|scacchi|sakk|schaken|szachy",_locale:"af_ZA|ar_AR|az_AZ|be_BY|bg_BG|bn_BD|bs_BA|ca|cs_CZ|da_DK|de_DE|el_GR|en_US|es_ES|et_EE|eu_ES|fa_IR|fi_FI|fil_PH|fr_FR|gl_ES|he_IL|hi_IN|hr_HR|hu_HU|hy_AM|id_ID|is_IS|it_IT|ja_JP|ka_GE|ko_KR|lt_LT|lv_LV|nl_BE|ms_MY|nl_NL|no_NO|pl_PL|pt_BR|pt_PT|ro_RO|ru_RU|sk_SK|sl_SI|sq_AL|sr_RS|sv_SE|tk_TM|tr_TR|uk_UA|ur_PK|vi_VN|zh_CN|zh_HK|zh_TW"},hosttokens:[["text",".chess.com"],["variable","","www|schach|ru|uk|ajedrez|fr|scacchi|sakk|schaken|szachy","subdomain"]],methods:["POST"],schemes:["https"]}}),((typeof self<"u"?self:this).wpChessCom_U1oS=(typeof self<"u"?self:this).wpChessCom_U1oS||[]).push([[13038],{82742:function(v,b,n){"use strict";var e=n(87099),u=n(94124),y=n(89640);const E=(o,m)=>{o.dataset.chessSrc=m.value.src,o.dataset.chessSrcset=m.value.srcset},f=o=>{const m=o.dataset.chessSrc,w=o.dataset.chessSrcset!=="false"?`${(0,y.q)(m)} 2x`:void 0;m&&o.setAttribute("src",m),w&&o.setAttribute("srcset",w)};b.Z={bind:(o,m)=>{m?.value&&(E(o,m),u.Z.on("visibility-observer-change",w=>{w===o&&(f(o),o.dataset.visible=!0,e.Z.unobserve(o))}),e.Z.observe(o))},componentUpdated(o,m){m?.value&&(E(o,m),typeof o.dataset.visible<"u"&&f(o))}}},87099:function(v,b,n){"use strict";var e=n(94124);let u=null;class y{constructor(){return u==null&&(u=this,this.observer=new IntersectionObserver(f=>{f.forEach(o=>{o.intersectionRatio>0&&(e.Z.emit("visibility-observer-change",o.target),this.observer.unobserve(o.target))})},{rootMargin:"200px 0px",threshold:.01})),u}observe(f){this.observer.observe(f)}unobserve(f){this.observer.unobserve(f)}}b.Z=new y},55475:function(v,b,n){"use strict";n.d(b,{X:function(){return u},S:function(){return y}});var e=n(67739);const u=E=>{Object.keys(E).forEach(f=>{const o=E[f];(!e.default.state||!e.default.state[f])&&e.default.registerModule(f,o)})},y=E=>{E.forEach(f=>{e.default.state&&e.default.state[f]&&e.default.unregisterModule(f)})}},44575:function(v,b,n){"use strict";var e=n(52619),u=n(98726),y=n(86371),E=n(49146),f=n(71646),o=n(46452),m=n(96290);const w=g=>{var c,t;return(t=(c=g.data)==null?void 0:c.message)!=null?t:e.R0.badRequest};b.Z={getUserTrophies:({commit:g},c)=>{const t={username:c.username,trophyType:c.trophyType};return f.Z.get(o.Z.generate("web_member_callback_trophy_list",t)).then(s=>{if(s.data)return g("setUserTrophies",s.data),y.Z.set("user_trophies",s.data,60),s.data}).catch(()=>{const s=e.V5.error;(0,u.el)({type:s,message:e.R0.badRequest})})},getAllTrophies:({commit:g,state:c},t)=>{g("setFinishedLoading",!1);const s=Object.assign({},{type:c.trophyType},t);return f.Z.get(o.Z.generate("web_callback_get_trophies",s)).then(r=>{r.data&&g("setAllTrophies",r.data)}).catch(()=>{const r=e.V5.error;(0,u.el)({type:r,message:e.R0.badRequest})})},getTrophyShowcase:({commit:g},c)=>f.Z.get(o.Z.generate("web_user_trophy_showcase_callback",{username:c})).then(t=>{t.data&&g("setUserShowcaseTrophies",t.data)}).catch(()=>{const t=e.V5.error;(0,u.el)({type:t,message:e.R0.badRequest})}),getUserTrophyCount:({commit:g},c)=>f.Z.get(o.Z.generate("web_callback_count_user_trophies",{username:c})).then(t=>{t.data&&g("setUserTrophiesCount",t.data.count)}).catch(()=>{const t=e.V5.error;(0,u.el)({type:t,message:e.R0.badRequest})}),featureTrophy:({dispatch:g},{trophyId:c,featured:t,username:s})=>{const r={id:c,featured:t};return f.Z.post(o.Z.generate("web_feature_trophy_callback",r)).then(_=>{const h=e.V5.success,p=w(_);(0,u.el)({type:h,message:p}),g("getTrophyShowcase",s)}).catch(()=>{const _=e.V5.error,h=m.Z.trans("Error featuring trophies. Please try again later.");(0,u.el)({type:_,message:h})})},sendTrophyToUser:({commit:g,state:c})=>{const t={username:c.recipientUsername,trophyId:c.selectedTrophy.id,message:(0,E.Ak)(c.message)};return typeof c.gameId=="number"&&(t[c.isLiveGame?"gameLiveId":"gameId"]=c.gameId),g("disableSendButton",!0),f.Z.post(o.Z.generate("web_award_trophy_callback",t)).then(()=>{g("disableSendButton",!1)}).catch(s=>(g("setErrorMessage",s.response.data.message),g("disableSendButton",!1),Promise.reject(s)))}}},33348:function(v,b,n){"use strict";var e=n(44575),u=n(85252),y=n(72521);b.Z={namespaced:!0,actions:e.Z,mutations:u.Z,state:y.Z}},85252:function(v,b){"use strict";b.Z={disableSendButton:(n,e)=>{n.disableSendButton=e},setUserTrophies:(n,e)=>{n.userTrophies=e},setAllTrophies:(n,e)=>{n.trophies=e.data,n.meta=e.meta,n.allTrophiesToGive=e.data,n.finishedLoadingTrophies=!0},setErrorMessage:(n,e)=>{n.errorMessage=e},setFinishedLoading:(n,e)=>{n.finishedLoadingTrophies=e},setGameId:(n,e)=>{n.gameId=e},setIsLiveGame:(n,e)=>{n.isLiveGame=e},setMessage:(n,e)=>{n.message=e},setModalState:(n,e)=>{n.modalState=e},setRecipientIsFriend:(n,e)=>{n.recipientIsFriend=e},setRecipientUsername:(n,e)=>{n.recipientUsername=e},setSelectedTrophy:(n,e)=>{n.selectedTrophy=e},setTrophyType:(n,e)=>{n.trophyType=e},setUserShowcaseTrophies:(n,e)=>{n.hideShowcase=e.length===0,n.userShowcaseTrophies=e},setUserTrophiesCount:(n,e)=>{n.userTrophyCount=e}}},72521:function(v,b,n){"use strict";var e=n(32128);b.Z={allTrophiesToGive:[],disableSendButton:!1,errorMessage:"",finishedLoadingTrophies:!1,gameId:null,hideShowcase:!1,isLiveGame:!1,message:"",meta:{currentPage:1,morePages:!1},recipientIsFriend:!1,modalState:e.Z.modalStates.select,recipientUsername:null,selectedTrophy:null,trophies:[],trophyType:"social",userShowcaseTrophies:[],userTrophies:[],userTrophyCount:0}},49146:function(v,b,n){"use strict";n.d(b,{KZ:function(){return u},CR:function(){return E},l_:function(){return f},Ak:function(){return o},_D:function(){return m},Io:function(){return w}});const e=c=>c.replace(/(<(?!img|\/?iframe)([^>]+)>)/gi,""),u=c=>c.replace(/&nbsp;/g,""),y=(c,t,s)=>c.split(t).join(s),E=c=>{const t=e(c);return u(t).trim()},f=c=>new DOMParser().parseFromString(`<!doctype html><body>${c}`,"text/html").body.textContent,o=c=>{if(!c)return c;const t=[60,62];let s=c.length;const r=[];for(;s--;){const _=c[s].charCodeAt();t.indexOf(_)>-1?r[s]=`&#${_};`:r[s]=c[s]}return r.join("")},m=c=>{var t;return(t=window.chesscom)!=null&&t.features.includes("remove_empty_space_for_comments")?String(c).replace(/p>(.*?)<\/p/g,s=>`p>${s.slice(2,-3).replace(/^(\s|&nbsp;)+|(\s|&nbsp;)+$/g,"")}</p`).replace(/>(.*?)<\//g,s=>`>${s.slice(1,-2).replace(/^(\s*<(br|BR)\s*\/?>)*|(<(br|BR)\s*\/?>\s*)*$/gm,"")}</`).replace(/>(.*?)<\//g,s=>`>${s.slice(1,-2).replace(/(\s*<(br|BR)\s*\/?>){2,}/g,"<br />").replace(/( |&nbsp;){2,}/g," ")}</`).replace(/<(div|p)*><\/[^>]+>/gim,"").trim():c},w=c=>{const t=window.chesscom.features.includes("user_mention_mail_link_issue")?/(^|[^a-zA-Z0-9_!#$%&*@＠/"])([@＠]([a-zA-Z0-9_-]{3,25}))/g:/(^|[^a-zA-Z0-9_!#$%&*@＠/])([@＠]([a-zA-Z0-9_-]{3,25}))/g;return String(c).replace(t,(s,r,_,h)=>`${r}<span class="v-user-popover" v-user-popover="'${h}'" data-username="'${h}'"> ${_}</span>`)};function g(c){const t=["onerror="];let s=c;return t.forEach(r=>{s=s.replace(r,"")}),t.some(r=>s.includes(r))?g(s):s}},21008:function(v,b,n){"use strict";n.d(b,{Kd:function(){return y},p6:function(){return m},uf:function(){return w}});var e=n(70158),u=n(96290);function y(t="",s="-"){return(t||(Object.prototype.hasOwnProperty.call(window,"context")?window.context.i18n.locale:"en-US")).replace("_",s)}const E=()=>{switch(new Date().getMonth()){case 3:case 5:case 8:case 10:return 864e5*30;case 1:return new Date().getFullYear()%4===0?864e5*29:864e5*28;default:return 864e5*31}};function f(t,s,r){return t==="year"?s?u.Z.transChoice("{1} 1 year ago|]1,Inf] %1$s% years ago",r,{"%1$s%":r}):u.Z.transChoice("{1} 1 year|]1,Inf] %1$s% years",r,{"%1$s%":r}):t==="month"?s?u.Z.transChoice("{1} 1 month ago|]1,Inf] %1$s% months ago",r,{"%1$s%":r}):u.Z.transChoice("{1} 1 month|]1,Inf] %1$s% months",r,{"%1$s%":r}):t==="day"?s?u.Z.transChoice("{1} 1 day ago|]1,Inf] %1$s% days ago",r,{"%1$s%":r}):u.Z.transChoice("{1} 1 day|]1,Inf] %1$s% days",r,{"%1$s%":r}):t==="hour"?s?u.Z.transChoice("{1} 1 hour ago|]1,Inf] %1$s% hours ago",r,{"%1$s%":r}):u.Z.transChoice("{1} 1 hour|]1,Inf] %1$s% hours",r,{"%1$s%":r}):t==="minute"?s?u.Z.transChoice("{1} 1 minute ago|]1,Inf] %1$s% minutes ago",r,{"%1$s%":r}):u.Z.transChoice("{1} 1 minute|]1,Inf] %1$s% minutes",r,{"%1$s%":r}):""}const o={year:31536e6,month:E(),day:864e5,hour:36e5,minute:6e4},m={mergeOptions(t){return{abbreviateHours:!1,abbreviateMinutes:!0,includeDays:!1,...t}},getUnitsInInt(t,s={}){const r=m.mergeOptions(s),_=86400,h=3600,p=60;let a=0,i=0,l=0,d=t;return r.includeDays===!0&&(a=Math.floor(d/_),d-=a*_),i=Math.floor(d/h),d-=i*h,l=Math.floor(d/p),{minutes:l,hours:i,days:a}},getDaysHoursMinutesAsString(t,s={}){const r=m.getDaysHoursMinutesAsObject(t,s),{minutes:_,hours:h,days:p}=m.getUnitsInInt(t,s),a=[];return p>0&&a.push(r.days),h>0&&a.push(r.hours),(_>0||a.length===0)&&a.push(r.minutes),a.join(" ")},getDaysHoursMinutesAsObject(t,s={}){const r=m.mergeOptions(s),{minutes:_,hours:h,days:p}=m.getUnitsInInt(t,s),a=u.Z.transChoice("{0} 0 days|{1} 1 day|]1,Inf] %1$s% days",p,{"%1$s%":p});let i=u.Z.transChoice("{0} 0 hours|{1} 1 hour|]1,Inf] %1$s% hours",Math.floor(h),{"%1$s%":h}),l=u.Z.transChoice("{0} 0 min|{1} 1 min|]1,Inf] %1$s% min",_,{"%1$s%":_});return r.abbreviateHours&&(i=u.Z.transChoice("{0} 0 hrs|{1} 1 hr|]1,Inf] %1$s% hrs",Math.floor(h),{"%1$s%":h})),r.abbreviateMinutes||(l=u.Z.transChoice("{1} 1 minute|]1,Inf] %1$s% minutes",_,{"%1$s%":_})),{days:a,hours:i,minutes:l}},long:(t=new Date,s={})=>{const r=t instanceof Date?t:new Date(t),_={year:"numeric",month:"short",day:"numeric"};return new Intl.DateTimeFormat(y(),{..._,...s}).format(r)},full:(t=new Date,s={})=>{const r=t instanceof Date?t:new Date(t),_={year:"numeric",month:"short",day:"numeric",hour:"numeric",minute:"numeric",second:"numeric",timeZoneName:"short"};return new Intl.DateTimeFormat(y(),{..._,...s}).format(r)},numeric:(t=new Date,s={})=>{const r=t instanceof Date?t:new Date(t);if(window.Intl){const p=y();return new Intl.DateTimeFormat(p,Object.assign({day:"2-digit",month:"2-digit",year:"numeric"},s)).format(r)}const _=`0${r.getMonth()+1}`.slice(-2),h=`0${r.getDate()}`.slice(-2);return`${_}/${h}/${r.getFullYear()}`},relative:(t,s=!0,r=!0,_=!1,h=Date.now())=>{let p,a;const l=(t instanceof Date?t:new Date(t)).getTime(),d=_?l-h:h-l;if(Math.abs(d)>o.month&&s){const k={year:"numeric",month:"short",day:"numeric"};return new Intl.DateTimeFormat(y(),k).format(l)}if(d>=o.year)a="year",p=Math.abs(Math.floor(d/o.year));else if(d>=o.month)a="month",p=Math.abs(Math.floor(d/o.month));else if(d>=o.day)a="day",p=Math.abs(Math.floor(d/o.day));else if(d>=o.hour)a="hour",p=Math.abs(Math.floor(d/o.hour));else if(d>=o.minute)a="minute",p=Math.abs(Math.floor(d/o.minute));else return _?u.Z.trans("Right now"):u.Z.trans("Just now");const I=Math.abs(d)===d;return f(a,I&&r,p)},customNumericDate:(t=new Date,s="/",r="m/d/y")=>{const _=t instanceof Date?t:new Date(t),h={d:`0${_.getDate()}`.slice(-2),m:`0${_.getMonth()+1}`.slice(-2),y:`${_.getFullYear()}`};return["m","d","y"].every(i=>r.split("/").includes(i))?r.split("/").reduce((i,l)=>(i.push(h[l]),i),[]).join(s):`${h.m}${s}${h.d}${s}${h.y}`},userDate:t=>{const s=window.context.user?new Date(t*1e3).toLocaleString("en-US",{timeZone:window.context.user.timezone}):new Date(t*1e3),r=new Date(s).setMilliseconds(0),_=new Date(r),h=e.Iz.daysOfWeek[_.getDay()],p=_.getDate(),a=e.Iz.months[_.getMonth()],i=_.toLocaleTimeString(y()),l=i.split(" ").length>1?i.split(" ")[1]:"",d=i.split(" ")[0].split(":");d.pop();const I=d.join(":");return`${h}, ${a} ${p}, ${I}${l}`}};function w(t,s="",r={}){return new Intl.NumberFormat([y(s),"en-US"],r).format(t)}function g(t,s=0){if(t<0||t==null)return"";const _=(t%1e3/1e3).toFixed(s+1).slice(2,s+2),h=Math.floor(t/1e3),p=Math.floor(h/60),a=Math.floor(p/60),i=p%60,l=i<10&&a?`0${i}`:i,d=h%60,I=d<10?`0${d}`:d;let k=`${l}:${I}`;return a&&(k=`${a}:${k}`),s&&(k=`${k}.${_}`),k}var c={formatDate:m,getLocale:y,formatNumber:w,formatTime:g}},52619:function(v,b,n){"use strict";n.d(b,{jC:function(){return u},R0:function(){return y},sY:function(){return E},xL:function(){return f},V5:function(){return o}});var e=n(96290);const u=7e3,y={badRequest:e.Z.trans("Oops. Looks like there was an error. Sorry! Please refresh.")},E={avatarUploaded:e.Z.trans("Avatar uploaded"),commentCreated:e.Z.trans("Thank you for your comment."),commentDeleted:e.Z.trans("Comment deleted."),commentEmptyContent:e.Z.trans("You cannot send an empty comment"),commentUpdated:e.Z.trans("Comment updated."),commentLinkCopied:e.Z.trans("Comment link is copied to clipboard"),copied:e.Z.trans("Copied to clipboard"),forumsMarkedAsRead:e.Z.trans("All forum topics have been marked as read"),featureContent:e.Z.trans("Feature Set! It might take a minute or two to appear."),inviteEmpty:e.Z.trans("Please select the players for whom to send the invitation"),inviteSent:e.Z.trans("Your invitations were sent successfully"),pgnCopied:e.Z.trans("PGN copied to buffer")},f={ALERT_FLASH_CONTAINER:"widget-alert-flash",DISMISSING:"alert-banner-dismissing"},o={error:"error",info:"info",success:"success"},m={type:o.error,message:y.badRequest}},98726:function(v,b,n){"use strict";n.d(b,{el:function(){return _},OD:function(){return y},x2:function(){return p},s$:function(){return h}});var e=n(52619);function u(a){const i=document.querySelector(`link[data-href*="/${a}.client"]`);i&&(i.setAttribute("href",i.getAttribute("data-href")),i.removeAttribute("data-href"))}function y(a){const i=document.getElementById(`alert-${a}`);i&&w(i)}function E(a=2){var i;const l=m();Array.from((i=l?.querySelectorAll(`.alerts-alert:not(:nth-last-child(-n + ${a}))`))!=null?i:[]).forEach(w)}function f(){const a=m();a&&(a.innerHTML="")}function o(a){var i;a.type!==e.V5.success&&E(2),u("alerts");const l=document.createElement("div");l.classList.add("alerts-alert"),l.classList.add("alerts-enter"),l.classList.add(`alerts-${a.type}`),l.setAttribute("id",`alert-${a.id}`);const d=document.createElement("span");d.classList.add("alerts-message"),d.innerHTML=a.message;const I=document.createElement("button");I.classList.add("alerts-close"),I.setAttribute("type","button"),I.innerHTML='<span class="icon-font-chess x"></span>',l.appendChild(d),l.appendChild(I),(i=m())==null||i.appendChild(l),I.addEventListener("click",c),a.type===e.V5.success&&setTimeout(w,e.jC,l)}function m(){return document.getElementById(e.xL.ALERT_FLASH_CONTAINER)}function w(a){a.classList.add("alerts-leave"),setTimeout(g,200,a)}function g(a){var i;try{(i=m())==null||i.removeChild(a)}catch{f()}}function c(a){var i,l;const d=(l=(i=a.target)==null?void 0:i.closest)==null?void 0:l.call(i,".alerts-alert");d&&w(d)}var t=n(54752),s=n.n(t);const r=(a,i)=>{const l=document.getElementById(`alert-${a}`);l&&l.classList.add(refs.DISMISSING),setTimeout(()=>{i()},200)};function _({message:a,type:i,id:l}){o({id:l??s()(),type:i,message:a})}function h(a){_({type:e.V5.success,message:a})}function p(a=e.R0.badRequest){_({type:e.V5.error,message:a})}},32128:function(v,b){"use strict";b.Z={modalStates:{select:"select",send:"send",sent:"sent"},types:{game:"game",social:"social"}}},67739:function(v,b,n){v.exports=n(84474)(717)}}]);