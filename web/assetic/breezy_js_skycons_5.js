(function(global){"use strict";var requestInterval,cancelInterval;(function(){var raf=global.requestAnimationFrame||global.webkitRequestAnimationFrame||global.mozRequestAnimationFrame||global.oRequestAnimationFrame||global.msRequestAnimationFrame,caf=global.cancelAnimationFrame||global.webkitCancelAnimationFrame||global.mozCancelAnimationFrame||global.oCancelAnimationFrame||global.msCancelAnimationFrame;if(raf&&caf){requestInterval=function(fn,delay){var handle={value:null};function loop(){handle.value=raf(loop);fn()}loop();return handle};cancelInterval=function(handle){caf(handle.value)}}else{requestInterval=setInterval;cancelInterval=clearInterval}})();var KEYFRAME=500,STROKE=.08,TAU=2*Math.PI,TWO_OVER_SQRT_2=2/Math.sqrt(2);function circle(ctx,x,y,r){ctx.beginPath();ctx.arc(x,y,r,0,TAU,false);ctx.fill()}function line(ctx,ax,ay,bx,by){ctx.beginPath();ctx.moveTo(ax,ay);ctx.lineTo(bx,by);ctx.stroke()}function puff(ctx,t,cx,cy,rx,ry,rmin,rmax){var c=Math.cos(t*TAU),s=Math.sin(t*TAU);rmax-=rmin;circle(ctx,cx-s*rx,cy+c*ry+rmax*.5,rmin+(1-c*.5)*rmax)}function puffs(ctx,t,cx,cy,rx,ry,rmin,rmax){var i;for(i=5;i--;)puff(ctx,t+i/5,cx,cy,rx,ry,rmin,rmax)}function cloud(ctx,t,cx,cy,cw,s,color){t/=3e4;var a=cw*.21,b=cw*.12,c=cw*.24,d=cw*.28;ctx.fillStyle=color;puffs(ctx,t,cx,cy,a,b,c,d);ctx.globalCompositeOperation="destination-out";puffs(ctx,t,cx,cy,a,b,c-s,d-s);ctx.globalCompositeOperation="source-over"}function sun(ctx,t,cx,cy,cw,s,color){t/=12e4;var a=cw*.25-s*.5,b=cw*.32+s*.5,c=cw*.5-s*.5,i,p,cos,sin;ctx.strokeStyle=color;ctx.lineWidth=s;ctx.lineCap="round";ctx.lineJoin="round";ctx.beginPath();ctx.arc(cx,cy,a,0,TAU,false);ctx.stroke();for(i=8;i--;){p=(t+i/8)*TAU;cos=Math.cos(p);sin=Math.sin(p);line(ctx,cx+cos*b,cy+sin*b,cx+cos*c,cy+sin*c)}}function moon(ctx,t,cx,cy,cw,s,color){t/=15e3;var a=cw*.29-s*.5,b=cw*.05,c=Math.cos(t*TAU),p=c*TAU/-16;ctx.strokeStyle=color;ctx.lineWidth=s;ctx.lineCap="round";ctx.lineJoin="round";cx+=c*b;ctx.beginPath();ctx.arc(cx,cy,a,p+TAU/8,p+TAU*7/8,false);ctx.arc(cx+Math.cos(p)*a*TWO_OVER_SQRT_2,cy+Math.sin(p)*a*TWO_OVER_SQRT_2,a,p+TAU*5/8,p+TAU*3/8,true);ctx.closePath();ctx.stroke()}function rain(ctx,t,cx,cy,cw,s,color){t/=1350;var a=cw*.16,b=TAU*11/12,c=TAU*7/12,i,p,x,y;ctx.fillStyle=color;for(i=4;i--;){p=(t+i/4)%1;x=cx+(i-1.5)/1.5*(i===1||i===2?-1:1)*a;y=cy+p*p*cw;ctx.beginPath();ctx.moveTo(x,y-s*1.5);ctx.arc(x,y,s*.75,b,c,false);ctx.fill()}}function sleet(ctx,t,cx,cy,cw,s,color){t/=750;var a=cw*.1875,b=TAU*11/12,c=TAU*7/12,i,p,x,y;ctx.strokeStyle=color;ctx.lineWidth=s*.5;ctx.lineCap="round";ctx.lineJoin="round";for(i=4;i--;){p=(t+i/4)%1;x=Math.floor(cx+(i-1.5)/1.5*(i===1||i===2?-1:1)*a)+.5;y=cy+p*cw;line(ctx,x,y-s*1.5,x,y+s*1.5)}}function snow(ctx,t,cx,cy,cw,s,color){t/=3e3;var a=cw*.16,b=s*.75,u=t*TAU*.7,ux=Math.cos(u)*b,uy=Math.sin(u)*b,v=u+TAU/3,vx=Math.cos(v)*b,vy=Math.sin(v)*b,w=u+TAU*2/3,wx=Math.cos(w)*b,wy=Math.sin(w)*b,i,p,x,y;ctx.strokeStyle=color;ctx.lineWidth=s*.5;ctx.lineCap="round";ctx.lineJoin="round";for(i=4;i--;){p=(t+i/4)%1;x=cx+Math.sin((p+i/4)*TAU)*a;y=cy+p*cw;line(ctx,x-ux,y-uy,x+ux,y+uy);line(ctx,x-vx,y-vy,x+vx,y+vy);line(ctx,x-wx,y-wy,x+wx,y+wy)}}function fogbank(ctx,t,cx,cy,cw,s,color){t/=3e4;var a=cw*.21,b=cw*.06,c=cw*.21,d=cw*.28;ctx.fillStyle=color;puffs(ctx,t,cx,cy,a,b,c,d);ctx.globalCompositeOperation="destination-out";puffs(ctx,t,cx,cy,a,b,c-s,d-s);ctx.globalCompositeOperation="source-over"}var WIND_PATHS=[[-.75,-.18,-.7219,-.1527,-.6971,-.1225,-.6739,-.091,-.6516,-.0588,-.6298,-.0262,-.6083,.0065,-.5868,.0396,-.5643,.0731,-.5372,.1041,-.5033,.1259,-.4662,.1406,-.4275,.1493,-.3881,.153,-.3487,.1526,-.3095,.1488,-.2708,.1421,-.2319,.1342,-.1943,.1217,-.16,.1025,-.129,.0785,-.1012,.0509,-.0764,.0206,-.0547,-.012,-.0378,-.0472,-.0324,-.0857,-.0389,-.1241,-.0546,-.1599,-.0814,-.1876,-.1193,-.1964,-.1582,-.1935,-.1931,-.1769,-.2157,-.1453,-.229,-.1085,-.2327,-.0697,-.224,-.0317,-.2064,.0033,-.1853,.0362,-.1613,.0672,-.135,.0961,-.1051,.1213,-.0706,.1397,-.0332,.1512,.0053,.158,.0442,.1624,.0833,.1636,.1224,.1615,.1613,.1565,.1999,.15,.2378,.1402,.2749,.1279,.3118,.1147,.3487,.1015,.3858,.0892,.4236,.0787,.4621,.0715,.5012,.0702,.5398,.0766,.5768,.089,.6123,.1055,.6466,.1244,.6805,.144,.7147,.163,.75,.18],[-.75,0,-.7033,.0195,-.6569,.0399,-.6104,.06,-.5634,.0789,-.5155,.0954,-.4667,.1089,-.4174,.1206,-.3676,.1299,-.3174,.1365,-.2669,.1398,-.2162,.1391,-.1658,.1347,-.1157,.1271,-.0661,.1169,-.017,.1046,.0316,.0903,.0791,.0728,.1259,.0534,.1723,.0331,.2188,.0129,.2656,-.0064,.3122,-.0263,.3586,-.0466,.4052,-.0665,.4525,-.0847,.5007,-.1002,.5497,-.113,.5991,-.124,.6491,-.1325,.6994,-.138,.75,-.14]],WIND_OFFSETS=[{start:.36,end:.11},{start:.56,end:.16}];function leaf(ctx,t,x,y,cw,s,color){var a=cw/8,b=a/3,c=2*b,d=t%1*TAU,e=Math.cos(d),f=Math.sin(d);ctx.fillStyle=color;ctx.strokeStyle=color;ctx.lineWidth=s;ctx.lineCap="round";ctx.lineJoin="round";ctx.beginPath();ctx.arc(x,y,a,d,d+Math.PI,false);ctx.arc(x-b*e,y-b*f,c,d+Math.PI,d,false);ctx.arc(x+c*e,y+c*f,b,d+Math.PI,d,true);ctx.globalCompositeOperation="destination-out";ctx.fill();ctx.globalCompositeOperation="source-over";ctx.stroke()}function swoosh(ctx,t,cx,cy,cw,s,index,total,color){t/=2500;var path=WIND_PATHS[index],a=(t+index-WIND_OFFSETS[index].start)%total,c=(t+index-WIND_OFFSETS[index].end)%total,e=(t+index)%total,b,d,f,i;ctx.strokeStyle=color;ctx.lineWidth=s;ctx.lineCap="round";ctx.lineJoin="round";if(a<1){ctx.beginPath();a*=path.length/2-1;b=Math.floor(a);a-=b;b*=2;b+=2;ctx.moveTo(cx+(path[b-2]*(1-a)+path[b]*a)*cw,cy+(path[b-1]*(1-a)+path[b+1]*a)*cw);if(c<1){c*=path.length/2-1;d=Math.floor(c);c-=d;d*=2;d+=2;for(i=b;i!==d;i+=2)ctx.lineTo(cx+path[i]*cw,cy+path[i+1]*cw);ctx.lineTo(cx+(path[d-2]*(1-c)+path[d]*c)*cw,cy+(path[d-1]*(1-c)+path[d+1]*c)*cw)}else for(i=b;i!==path.length;i+=2)ctx.lineTo(cx+path[i]*cw,cy+path[i+1]*cw);ctx.stroke()}else if(c<1){ctx.beginPath();c*=path.length/2-1;d=Math.floor(c);c-=d;d*=2;d+=2;ctx.moveTo(cx+path[0]*cw,cy+path[1]*cw);for(i=2;i!==d;i+=2)ctx.lineTo(cx+path[i]*cw,cy+path[i+1]*cw);ctx.lineTo(cx+(path[d-2]*(1-c)+path[d]*c)*cw,cy+(path[d-1]*(1-c)+path[d+1]*c)*cw);ctx.stroke()}if(e<1){e*=path.length/2-1;f=Math.floor(e);e-=f;f*=2;f+=2;leaf(ctx,t,cx+(path[f-2]*(1-e)+path[f]*e)*cw,cy+(path[f-1]*(1-e)+path[f+1]*e)*cw,cw,s,color)}}var Skycons=function(opts){this.list=[];this.interval=null;this.color=opts&&opts.color?opts.color:"black";this.resizeClear=!!(opts&&opts.resizeClear)};Skycons.CLEAR_DAY=function(ctx,t,color){var w=ctx.canvas.width,h=ctx.canvas.height,s=Math.min(w,h);sun(ctx,t,w*.5,h*.5,s,s*STROKE,color)};Skycons.CLEAR_NIGHT=function(ctx,t,color){var w=ctx.canvas.width,h=ctx.canvas.height,s=Math.min(w,h);moon(ctx,t,w*.5,h*.5,s,s*STROKE,color)};Skycons.PARTLY_CLOUDY_DAY=function(ctx,t,color){var w=ctx.canvas.width,h=ctx.canvas.height,s=Math.min(w,h);sun(ctx,t,w*.625,h*.375,s*.75,s*STROKE,color);cloud(ctx,t,w*.375,h*.625,s*.75,s*STROKE,color)};Skycons.PARTLY_CLOUDY_NIGHT=function(ctx,t,color){var w=ctx.canvas.width,h=ctx.canvas.height,s=Math.min(w,h);moon(ctx,t,w*.667,h*.375,s*.75,s*STROKE,color);cloud(ctx,t,w*.375,h*.625,s*.75,s*STROKE,color)};Skycons.CLOUDY=function(ctx,t,color){var w=ctx.canvas.width,h=ctx.canvas.height,s=Math.min(w,h);cloud(ctx,t,w*.5,h*.5,s,s*STROKE,color)};Skycons.RAIN=function(ctx,t,color){var w=ctx.canvas.width,h=ctx.canvas.height,s=Math.min(w,h);rain(ctx,t,w*.5,h*.37,s*.9,s*STROKE,color);cloud(ctx,t,w*.5,h*.37,s*.9,s*STROKE,color)};Skycons.SLEET=function(ctx,t,color){var w=ctx.canvas.width,h=ctx.canvas.height,s=Math.min(w,h);sleet(ctx,t,w*.5,h*.37,s*.9,s*STROKE,color);cloud(ctx,t,w*.5,h*.37,s*.9,s*STROKE,color)};Skycons.SNOW=function(ctx,t,color){var w=ctx.canvas.width,h=ctx.canvas.height,s=Math.min(w,h);snow(ctx,t,w*.5,h*.37,s*.9,s*STROKE,color);cloud(ctx,t,w*.5,h*.37,s*.9,s*STROKE,color)};Skycons.WIND=function(ctx,t,color){var w=ctx.canvas.width,h=ctx.canvas.height,s=Math.min(w,h);swoosh(ctx,t,w*.5,h*.5,s,s*STROKE,0,2,color);swoosh(ctx,t,w*.5,h*.5,s,s*STROKE,1,2,color)};Skycons.FOG=function(ctx,t,color){var w=ctx.canvas.width,h=ctx.canvas.height,s=Math.min(w,h),k=s*STROKE;fogbank(ctx,t,w*.5,h*.32,s*.75,k,color);t/=5e3;var a=Math.cos(t*TAU)*s*.02,b=Math.cos((t+.25)*TAU)*s*.02,c=Math.cos((t+.5)*TAU)*s*.02,d=Math.cos((t+.75)*TAU)*s*.02,n=h*.936,e=Math.floor(n-k*.5)+.5,f=Math.floor(n-k*2.5)+.5;ctx.strokeStyle=color;ctx.lineWidth=k;ctx.lineCap="round";ctx.lineJoin="round";line(ctx,a+w*.2+k*.5,e,b+w*.8-k*.5,e);line(ctx,c+w*.2+k*.5,f,d+w*.8-k*.5,f)};Skycons.prototype={_determineDrawingFunction:function(draw){if(typeof draw==="string")draw=Skycons[draw.toUpperCase().replace(/-/g,"_")]||null;return draw},add:function(el,draw){var obj;if(typeof el==="string")el=document.getElementById(el);if(el===null)return;draw=this._determineDrawingFunction(draw);if(typeof draw!=="function")return;obj={element:el,context:el.getContext("2d"),drawing:draw};this.list.push(obj);this.draw(obj,KEYFRAME)},set:function(el,draw){var i;if(typeof el==="string")el=document.getElementById(el);for(i=this.list.length;i--;)if(this.list[i].element===el){this.list[i].drawing=this._determineDrawingFunction(draw);this.draw(this.list[i],KEYFRAME);return}this.add(el,draw)},remove:function(el){var i;if(typeof el==="string")el=document.getElementById(el);for(i=this.list.length;i--;)if(this.list[i].element===el){this.list.splice(i,1);return}},draw:function(obj,time){var canvas=obj.context.canvas;if(this.resizeClear)canvas.width=canvas.width;else obj.context.clearRect(0,0,canvas.width,canvas.height);obj.drawing(obj.context,time,this.color)},play:function(){var self=this;this.pause();this.interval=requestInterval(function(){var now=Date.now(),i;for(i=self.list.length;i--;)self.draw(self.list[i],now)},1e3/60)},pause:function(){var i;if(this.interval){cancelInterval(this.interval);this.interval=null}}};global.Skycons=Skycons})(this);