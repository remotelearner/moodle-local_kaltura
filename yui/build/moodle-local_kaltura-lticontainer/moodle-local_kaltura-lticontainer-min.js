YUI.add("moodle-local_kaltura-lticontainer",function(e,t){var n=function(){n.superclass.constructor.apply(this,arguments)};e.extend(n,e.Base,{lastheight:null,padding:15,viewportheight:null,documentheight:null,clientheight:null,kalvidwidth:null,ltiframe:null,init:function(t){var n=e.one("body[class~="+t.bodyclass+"]");this.lastheight=t.lastheight,this.padding=t.padding,this.viewportheight=n.get("winHeight"),this.documentheight=n.get("docHeight"),this.clientheight=n.getDOMNode.clientHeight,this.ltiframe=e.one("#contentframe"),this.kalvidwidth=t.kalvidwidth,this.resize(),this.timer=e.later(250,this,this.resize)},resize:function(){if(this.lastheight!==Math.min(this.documentheight,this.viewportheight)){var t=this.viewportheight-this.ltiframe.getY()-this.padding+"px";this.ltiframe.setStyle("height",t),this.lastheight=Math.min(this.documentheight,this.viewportheight)}var n=e.one("#kalvid_content");if(n!==null){var r=n.get("offsetWidth"),i=r-this.padding;if(this.kalvidwidth!==null){var s=this.kalvidwidth*2;s>i?this.ltiframe.setStyle("width",i+"px"):this.ltiframe.setStyle("width",s+"px")}}}},{NAME:"moodle-local_kaltura-lticontainer",ATTRS:{bodyclass:{value:null},lastheight:{value:null},padding:{value:15}}}),M.local_kaltura=M.local_kaltura||{},M.local_kaltura.init=function(e){return new n(e)}},"@VERSION@",{requires:["base","node"]});
