"use strict";((typeof self<"u"?self:this).wpChessCom_i9Sk=(typeof self<"u"?self:this).wpChessCom_i9Sk||[]).push([[442],{8442:function(Hn,I,B){B.r(I),B.d(I,{createRenderer:function(){return K}});var n=B(2117),On=`.coordinate-light, .coordinate-dark {
  font-weight: 600;
}

.coordinate-grey {
  fill: rgba(255, 255, 255, 0.5);
  font-weight: 600;
}

.coordinates {
  /*rtl:ignore*/
  left: 0;
  position: absolute;
  top: 0;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
}

.outside {
  position: absolute;
  transform: translate(-5%, 4%);
}

.outside text {
  font-size: clamp(0.1rem, 0.25rem, 0.25rem);
}

html[dir=rtl] .coordinates:not(.outside) {
  /*rtl:ignore*/
  left: 8px;
  overflow: visible;
}

html[dir=rtl] .outside {
  /*rtl:ignore*/
  transform: translate(-3%, 4%);
}`,$;(function(e){e[e.Slide=0]="Slide",e[e.FadeOut=1]="FadeOut"})($||($={}));function U(e,t){const r=(0,n.g)(e);return t?{file:9-r.file,rank:9-r.rank}:r}function O(e,t){const r=U(e,t);return{x:r.file*100-100,y:(8-r.rank)*100}}const F="element-pool";var Ln=`.element-pool {
  position: absolute;
  transform: translateX(-10000px);
}`;function M({appendTo:e,elementType:t="div",insertBefore:r,startingCount:o=0}){if(!e&&!r)throw new n.C({code:n.E.BadData,message:'When creating an element pool, you must provide an element to "appendTo" or "insertBefore".'});const d=Array(o).fill(void 0).map(u).map(f);return{destroy:i,get:l,put:m};function u(){var s;const a=document.createElement(t);return a.className=F,e?e.appendChild(a):(s=r?.parentNode)==null||s.insertBefore(a,r),a}function i(){d.forEach(s=>{var a;return(a=s.parentNode)==null?void 0:a.removeChild(s)}),d.length=0}function l(){const s=d.pop()||u();return x(s)}function f(s){for(const a in s.dataset)s.dataset[a]&&(s.dataset[a]="");return s.className=F,s.style.cssText="",s}function m(s){return f(s),d.push(s),s}function x(s){return s.className="",s}}var E;(function(e){e.Created="Created",e.DetailsSet="DetailsSet",e.DragEnded="DragEnd",e.DragStarted="DragStart",e.PieceShown="PieceShown",e.PieceHidden="PieceHidden",e.PositionSetBySquare="PositionSetBySquare"})(E||(E={}));var V=B(5653),Q=Object.defineProperty,J=Object.defineProperties,Y=Object.getOwnPropertyDescriptors,N=Object.getOwnPropertySymbols,Z=Object.prototype.hasOwnProperty,nn=Object.prototype.propertyIsEnumerable,W=(e,t,r)=>t in e?Q(e,t,{enumerable:!0,configurable:!0,writable:!0,value:r}):e[t]=r,S=(e,t)=>{for(var r in t||(t={}))Z.call(t,r)&&W(e,r,t[r]);if(N)for(var r of N(t))nn.call(t,r)&&W(e,r,t[r]);return e},C=(e,t)=>J(e,Y(t)),In=`.board {
  background-repeat: no-repeat;
  background-size: 100%;
  border-radius: 3px;
  contain: layout;
  height: 100%;
  position: relative;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
  width: 100%;
}
.board.pseudo3d .piece,
.board.pseudo3d .promotion-piece {
  overflow: visible;
}
.board.pseudo3d .piece::after,
.board.pseudo3d .promotion-piece::after {
  background-position-y: bottom;
  background-repeat: no-repeat;
  background-size: contain;
  bottom: 0;
  content: " ";
  height: 132%;
  left: 0;
  position: absolute;
  width: 100%;
}
.piece {
  background-size: 100%;
  cursor: pointer;
  cursor: grab;
  cursor: -webkit-grab;
  height: 12.5%;
  /*rtl:ignore*/
  left: 0;
  overflow: hidden;
  position: absolute;
  top: 0;
  touch-action: none;
  width: 12.5%;
  will-change: transform;
}
.piece.dragging {
  cursor: grabbing;
  cursor: -webkit-grabbing;
  z-index: 2;
}
.pseudo3d .piece.dragging, .pseudo3d.flipped .piece.dragging {
  z-index: 10;
}
.highlight,
.hover-square {
  height: 12.5%;
  /*rtl:ignore*/
  left: 0;
  pointer-events: none;
  position: absolute;
  top: 0;
  width: 12.5%;
}
.hover-square {
  background: none;
}
.highlight,
.hint,
.capture-hint {
  height: 12.5%;
  /*rtl:ignore*/
  left: 0;
  position: absolute;
  top: 0;
  width: 12.5%;
}
.disabled .piece {
  cursor: default;
}
.hint,
.capture-hint {
  background-clip: content-box;
  border-radius: 50%;
  box-sizing: border-box;
  pointer-events: none;
}
.hint {
  background-color: rgba(0, 0, 0, 0.1);
  padding: 4.2%;
}
.capture-hint {
  border: 5px solid rgba(0, 0, 0, 0.1);
}
.promotion-window {
  background-color: white;
  border-radius: 3px;
  bottom: 0;
  box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.45);
  display: flex;
  flex-direction: column-reverse;
  height: 56.25%;
  /*rtl:ignore*/
  left: 0;
  position: absolute;
  top: auto;
  width: 12.5%;
  z-index: 2;
}
.pseudo3d .promotion-window, .pseudo3d.flipped .promotion-window {
  z-index: 10;
}
.promotion-window.top {
  bottom: auto;
  top: 0;
}
.promotion-window.top .close-button {
  border-radius: 0 0 3px 3px;
  order: 0;
}
.promotion-window.top .promotion-pieces {
  border-radius: 0 0 3px 3px;
}
.promotion-window.top .promotion-piece.wq, .promotion-window.top .promotion-piece.bq {
  order: 4;
}
.promotion-window.top .promotion-piece.wn, .promotion-window.top .promotion-piece.bn {
  order: 3;
}
.promotion-window.top .promotion-piece.wr, .promotion-window.top .promotion-piece.br {
  order: 2;
}
.promotion-window.top .promotion-piece.wb, .promotion-window.top .promotion-piece.bb {
  order: 1;
}
.pseudo3d .promotion-window.top .promotion-piece.wq, .pseudo3d .promotion-window.top .promotion-piece.bq {
  z-index: 11;
}
.pseudo3d .promotion-window.top .promotion-piece.wn, .pseudo3d .promotion-window.top .promotion-piece.bn {
  z-index: 12;
}
.pseudo3d .promotion-window.top .promotion-piece.wr, .pseudo3d .promotion-window.top .promotion-piece.br {
  z-index: 13;
}
.pseudo3d .promotion-window.top .promotion-piece.wb, .pseudo3d .promotion-window.top .promotion-piece.bb {
  z-index: 14;
}
.promotion-window .promotion-pieces {
  background: white;
  border-radius: 3px 3px 0 0;
}
.promotion-window .promotion-piece {
  background-position-y: bottom;
  background-repeat: no-repeat;
  background-size: 100%;
  cursor: pointer;
  padding-top: 100%;
  position: relative;
}
.promotion-window .promotion-piece.wq, .promotion-window .promotion-piece.bq {
  order: 0;
}
.promotion-window .promotion-piece.wn, .promotion-window .promotion-piece.bn {
  order: 1;
}
.promotion-window .promotion-piece.wr, .promotion-window .promotion-piece.br {
  order: 2;
}
.promotion-window .promotion-piece.wb, .promotion-window .promotion-piece.bb {
  order: 3;
}
.pseudo3d .promotion-window .promotion-piece.wq, .pseudo3d .promotion-window .promotion-piece.bq {
  z-index: 14;
}
.pseudo3d .promotion-window .promotion-piece.wn, .pseudo3d .promotion-window .promotion-piece.bn {
  z-index: 13;
}
.pseudo3d .promotion-window .promotion-piece.wr, .pseudo3d .promotion-window .promotion-piece.br {
  z-index: 12;
}
.pseudo3d .promotion-window .promotion-piece.wb, .pseudo3d .promotion-window .promotion-piece.bb {
  z-index: 11;
}
.promotion-window .close-button {
  align-items: center;
  background: #f1f1f1;
  border-radius: 4px 4px 0 0;
  color: #8b8987;
  cursor: pointer;
  display: flex;
  flex-grow: 1;
  font-size: 150%;
  font-style: normal;
  justify-content: center;
  max-height: 12.5%;
  order: 4;
  text-align: center;
}
.promotion-window .arrow-container {
  /*rtl:ignore*/
  left: 0;
  position: absolute;
  top: 0;
}
.square-11 {
  transform: translate(0%, 700%);
}
.pseudo3d .square-11 {
  z-index: 8;
}
.flipped .square-11 {
  transform: translate(700%, 0%);
}
.pseudo3d.flipped .square-11 {
  z-index: 1;
}
.square-21 {
  transform: translate(100%, 700%);
}
.pseudo3d .square-21 {
  z-index: 8;
}
.flipped .square-21 {
  transform: translate(600%, 0%);
}
.pseudo3d.flipped .square-21 {
  z-index: 1;
}
.square-31 {
  transform: translate(200%, 700%);
}
.pseudo3d .square-31 {
  z-index: 8;
}
.flipped .square-31 {
  transform: translate(500%, 0%);
}
.pseudo3d.flipped .square-31 {
  z-index: 1;
}
.square-41 {
  transform: translate(300%, 700%);
}
.pseudo3d .square-41 {
  z-index: 8;
}
.flipped .square-41 {
  transform: translate(400%, 0%);
}
.pseudo3d.flipped .square-41 {
  z-index: 1;
}
.square-51 {
  transform: translate(400%, 700%);
}
.pseudo3d .square-51 {
  z-index: 8;
}
.flipped .square-51 {
  transform: translate(300%, 0%);
}
.pseudo3d.flipped .square-51 {
  z-index: 1;
}
.square-61 {
  transform: translate(500%, 700%);
}
.pseudo3d .square-61 {
  z-index: 8;
}
.flipped .square-61 {
  transform: translate(200%, 0%);
}
.pseudo3d.flipped .square-61 {
  z-index: 1;
}
.square-71 {
  transform: translate(600%, 700%);
}
.pseudo3d .square-71 {
  z-index: 8;
}
.flipped .square-71 {
  transform: translate(100%, 0%);
}
.pseudo3d.flipped .square-71 {
  z-index: 1;
}
.square-81 {
  transform: translate(700%, 700%);
}
.pseudo3d .square-81 {
  z-index: 8;
}
.flipped .square-81 {
  transform: translate(0%, 0%);
}
.pseudo3d.flipped .square-81 {
  z-index: 1;
}
.square-12 {
  transform: translate(0%, 600%);
}
.pseudo3d .square-12 {
  z-index: 7;
}
.flipped .square-12 {
  transform: translate(700%, 100%);
}
.pseudo3d.flipped .square-12 {
  z-index: 2;
}
.square-22 {
  transform: translate(100%, 600%);
}
.pseudo3d .square-22 {
  z-index: 7;
}
.flipped .square-22 {
  transform: translate(600%, 100%);
}
.pseudo3d.flipped .square-22 {
  z-index: 2;
}
.square-32 {
  transform: translate(200%, 600%);
}
.pseudo3d .square-32 {
  z-index: 7;
}
.flipped .square-32 {
  transform: translate(500%, 100%);
}
.pseudo3d.flipped .square-32 {
  z-index: 2;
}
.square-42 {
  transform: translate(300%, 600%);
}
.pseudo3d .square-42 {
  z-index: 7;
}
.flipped .square-42 {
  transform: translate(400%, 100%);
}
.pseudo3d.flipped .square-42 {
  z-index: 2;
}
.square-52 {
  transform: translate(400%, 600%);
}
.pseudo3d .square-52 {
  z-index: 7;
}
.flipped .square-52 {
  transform: translate(300%, 100%);
}
.pseudo3d.flipped .square-52 {
  z-index: 2;
}
.square-62 {
  transform: translate(500%, 600%);
}
.pseudo3d .square-62 {
  z-index: 7;
}
.flipped .square-62 {
  transform: translate(200%, 100%);
}
.pseudo3d.flipped .square-62 {
  z-index: 2;
}
.square-72 {
  transform: translate(600%, 600%);
}
.pseudo3d .square-72 {
  z-index: 7;
}
.flipped .square-72 {
  transform: translate(100%, 100%);
}
.pseudo3d.flipped .square-72 {
  z-index: 2;
}
.square-82 {
  transform: translate(700%, 600%);
}
.pseudo3d .square-82 {
  z-index: 7;
}
.flipped .square-82 {
  transform: translate(0%, 100%);
}
.pseudo3d.flipped .square-82 {
  z-index: 2;
}
.square-13 {
  transform: translate(0%, 500%);
}
.pseudo3d .square-13 {
  z-index: 6;
}
.flipped .square-13 {
  transform: translate(700%, 200%);
}
.pseudo3d.flipped .square-13 {
  z-index: 3;
}
.square-23 {
  transform: translate(100%, 500%);
}
.pseudo3d .square-23 {
  z-index: 6;
}
.flipped .square-23 {
  transform: translate(600%, 200%);
}
.pseudo3d.flipped .square-23 {
  z-index: 3;
}
.square-33 {
  transform: translate(200%, 500%);
}
.pseudo3d .square-33 {
  z-index: 6;
}
.flipped .square-33 {
  transform: translate(500%, 200%);
}
.pseudo3d.flipped .square-33 {
  z-index: 3;
}
.square-43 {
  transform: translate(300%, 500%);
}
.pseudo3d .square-43 {
  z-index: 6;
}
.flipped .square-43 {
  transform: translate(400%, 200%);
}
.pseudo3d.flipped .square-43 {
  z-index: 3;
}
.square-53 {
  transform: translate(400%, 500%);
}
.pseudo3d .square-53 {
  z-index: 6;
}
.flipped .square-53 {
  transform: translate(300%, 200%);
}
.pseudo3d.flipped .square-53 {
  z-index: 3;
}
.square-63 {
  transform: translate(500%, 500%);
}
.pseudo3d .square-63 {
  z-index: 6;
}
.flipped .square-63 {
  transform: translate(200%, 200%);
}
.pseudo3d.flipped .square-63 {
  z-index: 3;
}
.square-73 {
  transform: translate(600%, 500%);
}
.pseudo3d .square-73 {
  z-index: 6;
}
.flipped .square-73 {
  transform: translate(100%, 200%);
}
.pseudo3d.flipped .square-73 {
  z-index: 3;
}
.square-83 {
  transform: translate(700%, 500%);
}
.pseudo3d .square-83 {
  z-index: 6;
}
.flipped .square-83 {
  transform: translate(0%, 200%);
}
.pseudo3d.flipped .square-83 {
  z-index: 3;
}
.square-14 {
  transform: translate(0%, 400%);
}
.pseudo3d .square-14 {
  z-index: 5;
}
.flipped .square-14 {
  transform: translate(700%, 300%);
}
.pseudo3d.flipped .square-14 {
  z-index: 4;
}
.square-24 {
  transform: translate(100%, 400%);
}
.pseudo3d .square-24 {
  z-index: 5;
}
.flipped .square-24 {
  transform: translate(600%, 300%);
}
.pseudo3d.flipped .square-24 {
  z-index: 4;
}
.square-34 {
  transform: translate(200%, 400%);
}
.pseudo3d .square-34 {
  z-index: 5;
}
.flipped .square-34 {
  transform: translate(500%, 300%);
}
.pseudo3d.flipped .square-34 {
  z-index: 4;
}
.square-44 {
  transform: translate(300%, 400%);
}
.pseudo3d .square-44 {
  z-index: 5;
}
.flipped .square-44 {
  transform: translate(400%, 300%);
}
.pseudo3d.flipped .square-44 {
  z-index: 4;
}
.square-54 {
  transform: translate(400%, 400%);
}
.pseudo3d .square-54 {
  z-index: 5;
}
.flipped .square-54 {
  transform: translate(300%, 300%);
}
.pseudo3d.flipped .square-54 {
  z-index: 4;
}
.square-64 {
  transform: translate(500%, 400%);
}
.pseudo3d .square-64 {
  z-index: 5;
}
.flipped .square-64 {
  transform: translate(200%, 300%);
}
.pseudo3d.flipped .square-64 {
  z-index: 4;
}
.square-74 {
  transform: translate(600%, 400%);
}
.pseudo3d .square-74 {
  z-index: 5;
}
.flipped .square-74 {
  transform: translate(100%, 300%);
}
.pseudo3d.flipped .square-74 {
  z-index: 4;
}
.square-84 {
  transform: translate(700%, 400%);
}
.pseudo3d .square-84 {
  z-index: 5;
}
.flipped .square-84 {
  transform: translate(0%, 300%);
}
.pseudo3d.flipped .square-84 {
  z-index: 4;
}
.square-15 {
  transform: translate(0%, 300%);
}
.pseudo3d .square-15 {
  z-index: 4;
}
.flipped .square-15 {
  transform: translate(700%, 400%);
}
.pseudo3d.flipped .square-15 {
  z-index: 5;
}
.square-25 {
  transform: translate(100%, 300%);
}
.pseudo3d .square-25 {
  z-index: 4;
}
.flipped .square-25 {
  transform: translate(600%, 400%);
}
.pseudo3d.flipped .square-25 {
  z-index: 5;
}
.square-35 {
  transform: translate(200%, 300%);
}
.pseudo3d .square-35 {
  z-index: 4;
}
.flipped .square-35 {
  transform: translate(500%, 400%);
}
.pseudo3d.flipped .square-35 {
  z-index: 5;
}
.square-45 {
  transform: translate(300%, 300%);
}
.pseudo3d .square-45 {
  z-index: 4;
}
.flipped .square-45 {
  transform: translate(400%, 400%);
}
.pseudo3d.flipped .square-45 {
  z-index: 5;
}
.square-55 {
  transform: translate(400%, 300%);
}
.pseudo3d .square-55 {
  z-index: 4;
}
.flipped .square-55 {
  transform: translate(300%, 400%);
}
.pseudo3d.flipped .square-55 {
  z-index: 5;
}
.square-65 {
  transform: translate(500%, 300%);
}
.pseudo3d .square-65 {
  z-index: 4;
}
.flipped .square-65 {
  transform: translate(200%, 400%);
}
.pseudo3d.flipped .square-65 {
  z-index: 5;
}
.square-75 {
  transform: translate(600%, 300%);
}
.pseudo3d .square-75 {
  z-index: 4;
}
.flipped .square-75 {
  transform: translate(100%, 400%);
}
.pseudo3d.flipped .square-75 {
  z-index: 5;
}
.square-85 {
  transform: translate(700%, 300%);
}
.pseudo3d .square-85 {
  z-index: 4;
}
.flipped .square-85 {
  transform: translate(0%, 400%);
}
.pseudo3d.flipped .square-85 {
  z-index: 5;
}
.square-16 {
  transform: translate(0%, 200%);
}
.pseudo3d .square-16 {
  z-index: 3;
}
.flipped .square-16 {
  transform: translate(700%, 500%);
}
.pseudo3d.flipped .square-16 {
  z-index: 6;
}
.square-26 {
  transform: translate(100%, 200%);
}
.pseudo3d .square-26 {
  z-index: 3;
}
.flipped .square-26 {
  transform: translate(600%, 500%);
}
.pseudo3d.flipped .square-26 {
  z-index: 6;
}
.square-36 {
  transform: translate(200%, 200%);
}
.pseudo3d .square-36 {
  z-index: 3;
}
.flipped .square-36 {
  transform: translate(500%, 500%);
}
.pseudo3d.flipped .square-36 {
  z-index: 6;
}
.square-46 {
  transform: translate(300%, 200%);
}
.pseudo3d .square-46 {
  z-index: 3;
}
.flipped .square-46 {
  transform: translate(400%, 500%);
}
.pseudo3d.flipped .square-46 {
  z-index: 6;
}
.square-56 {
  transform: translate(400%, 200%);
}
.pseudo3d .square-56 {
  z-index: 3;
}
.flipped .square-56 {
  transform: translate(300%, 500%);
}
.pseudo3d.flipped .square-56 {
  z-index: 6;
}
.square-66 {
  transform: translate(500%, 200%);
}
.pseudo3d .square-66 {
  z-index: 3;
}
.flipped .square-66 {
  transform: translate(200%, 500%);
}
.pseudo3d.flipped .square-66 {
  z-index: 6;
}
.square-76 {
  transform: translate(600%, 200%);
}
.pseudo3d .square-76 {
  z-index: 3;
}
.flipped .square-76 {
  transform: translate(100%, 500%);
}
.pseudo3d.flipped .square-76 {
  z-index: 6;
}
.square-86 {
  transform: translate(700%, 200%);
}
.pseudo3d .square-86 {
  z-index: 3;
}
.flipped .square-86 {
  transform: translate(0%, 500%);
}
.pseudo3d.flipped .square-86 {
  z-index: 6;
}
.square-17 {
  transform: translate(0%, 100%);
}
.pseudo3d .square-17 {
  z-index: 2;
}
.flipped .square-17 {
  transform: translate(700%, 600%);
}
.pseudo3d.flipped .square-17 {
  z-index: 7;
}
.square-27 {
  transform: translate(100%, 100%);
}
.pseudo3d .square-27 {
  z-index: 2;
}
.flipped .square-27 {
  transform: translate(600%, 600%);
}
.pseudo3d.flipped .square-27 {
  z-index: 7;
}
.square-37 {
  transform: translate(200%, 100%);
}
.pseudo3d .square-37 {
  z-index: 2;
}
.flipped .square-37 {
  transform: translate(500%, 600%);
}
.pseudo3d.flipped .square-37 {
  z-index: 7;
}
.square-47 {
  transform: translate(300%, 100%);
}
.pseudo3d .square-47 {
  z-index: 2;
}
.flipped .square-47 {
  transform: translate(400%, 600%);
}
.pseudo3d.flipped .square-47 {
  z-index: 7;
}
.square-57 {
  transform: translate(400%, 100%);
}
.pseudo3d .square-57 {
  z-index: 2;
}
.flipped .square-57 {
  transform: translate(300%, 600%);
}
.pseudo3d.flipped .square-57 {
  z-index: 7;
}
.square-67 {
  transform: translate(500%, 100%);
}
.pseudo3d .square-67 {
  z-index: 2;
}
.flipped .square-67 {
  transform: translate(200%, 600%);
}
.pseudo3d.flipped .square-67 {
  z-index: 7;
}
.square-77 {
  transform: translate(600%, 100%);
}
.pseudo3d .square-77 {
  z-index: 2;
}
.flipped .square-77 {
  transform: translate(100%, 600%);
}
.pseudo3d.flipped .square-77 {
  z-index: 7;
}
.square-87 {
  transform: translate(700%, 100%);
}
.pseudo3d .square-87 {
  z-index: 2;
}
.flipped .square-87 {
  transform: translate(0%, 600%);
}
.pseudo3d.flipped .square-87 {
  z-index: 7;
}
.square-18 {
  transform: translate(0%, 0%);
}
.pseudo3d .square-18 {
  z-index: 1;
}
.flipped .square-18 {
  transform: translate(700%, 700%);
}
.pseudo3d.flipped .square-18 {
  z-index: 8;
}
.square-28 {
  transform: translate(100%, 0%);
}
.pseudo3d .square-28 {
  z-index: 1;
}
.flipped .square-28 {
  transform: translate(600%, 700%);
}
.pseudo3d.flipped .square-28 {
  z-index: 8;
}
.square-38 {
  transform: translate(200%, 0%);
}
.pseudo3d .square-38 {
  z-index: 1;
}
.flipped .square-38 {
  transform: translate(500%, 700%);
}
.pseudo3d.flipped .square-38 {
  z-index: 8;
}
.square-48 {
  transform: translate(300%, 0%);
}
.pseudo3d .square-48 {
  z-index: 1;
}
.flipped .square-48 {
  transform: translate(400%, 700%);
}
.pseudo3d.flipped .square-48 {
  z-index: 8;
}
.square-58 {
  transform: translate(400%, 0%);
}
.pseudo3d .square-58 {
  z-index: 1;
}
.flipped .square-58 {
  transform: translate(300%, 700%);
}
.pseudo3d.flipped .square-58 {
  z-index: 8;
}
.square-68 {
  transform: translate(500%, 0%);
}
.pseudo3d .square-68 {
  z-index: 1;
}
.flipped .square-68 {
  transform: translate(200%, 700%);
}
.pseudo3d.flipped .square-68 {
  z-index: 8;
}
.square-78 {
  transform: translate(600%, 0%);
}
.pseudo3d .square-78 {
  z-index: 1;
}
.flipped .square-78 {
  transform: translate(100%, 700%);
}
.pseudo3d.flipped .square-78 {
  z-index: 8;
}
.square-88 {
  transform: translate(700%, 0%);
}
.pseudo3d .square-88 {
  z-index: 1;
}
.flipped .square-88 {
  transform: translate(0%, 700%);
}
.pseudo3d.flipped .square-88 {
  z-index: 8;
}
html[dir=rtl] .square-11 {
  transform: translate(0%, 700%);
}
html[dir=rtl] .flipped .square-11 {
  transform: translate(-700%, 0%);
}
html[dir=rtl] .square-21 {
  transform: translate(-100%, 700%);
}
html[dir=rtl] .flipped .square-21 {
  transform: translate(-600%, 0%);
}
html[dir=rtl] .square-31 {
  transform: translate(-200%, 700%);
}
html[dir=rtl] .flipped .square-31 {
  transform: translate(-500%, 0%);
}
html[dir=rtl] .square-41 {
  transform: translate(-300%, 700%);
}
html[dir=rtl] .flipped .square-41 {
  transform: translate(-400%, 0%);
}
html[dir=rtl] .square-51 {
  transform: translate(-400%, 700%);
}
html[dir=rtl] .flipped .square-51 {
  transform: translate(-300%, 0%);
}
html[dir=rtl] .square-61 {
  transform: translate(-500%, 700%);
}
html[dir=rtl] .flipped .square-61 {
  transform: translate(-200%, 0%);
}
html[dir=rtl] .square-71 {
  transform: translate(-600%, 700%);
}
html[dir=rtl] .flipped .square-71 {
  transform: translate(-100%, 0%);
}
html[dir=rtl] .square-81 {
  transform: translate(-700%, 700%);
}
html[dir=rtl] .flipped .square-81 {
  transform: translate(0%, 0%);
}
html[dir=rtl] .square-12 {
  transform: translate(0%, 600%);
}
html[dir=rtl] .flipped .square-12 {
  transform: translate(-700%, 100%);
}
html[dir=rtl] .square-22 {
  transform: translate(-100%, 600%);
}
html[dir=rtl] .flipped .square-22 {
  transform: translate(-600%, 100%);
}
html[dir=rtl] .square-32 {
  transform: translate(-200%, 600%);
}
html[dir=rtl] .flipped .square-32 {
  transform: translate(-500%, 100%);
}
html[dir=rtl] .square-42 {
  transform: translate(-300%, 600%);
}
html[dir=rtl] .flipped .square-42 {
  transform: translate(-400%, 100%);
}
html[dir=rtl] .square-52 {
  transform: translate(-400%, 600%);
}
html[dir=rtl] .flipped .square-52 {
  transform: translate(-300%, 100%);
}
html[dir=rtl] .square-62 {
  transform: translate(-500%, 600%);
}
html[dir=rtl] .flipped .square-62 {
  transform: translate(-200%, 100%);
}
html[dir=rtl] .square-72 {
  transform: translate(-600%, 600%);
}
html[dir=rtl] .flipped .square-72 {
  transform: translate(-100%, 100%);
}
html[dir=rtl] .square-82 {
  transform: translate(-700%, 600%);
}
html[dir=rtl] .flipped .square-82 {
  transform: translate(0%, 100%);
}
html[dir=rtl] .square-13 {
  transform: translate(0%, 500%);
}
html[dir=rtl] .flipped .square-13 {
  transform: translate(-700%, 200%);
}
html[dir=rtl] .square-23 {
  transform: translate(-100%, 500%);
}
html[dir=rtl] .flipped .square-23 {
  transform: translate(-600%, 200%);
}
html[dir=rtl] .square-33 {
  transform: translate(-200%, 500%);
}
html[dir=rtl] .flipped .square-33 {
  transform: translate(-500%, 200%);
}
html[dir=rtl] .square-43 {
  transform: translate(-300%, 500%);
}
html[dir=rtl] .flipped .square-43 {
  transform: translate(-400%, 200%);
}
html[dir=rtl] .square-53 {
  transform: translate(-400%, 500%);
}
html[dir=rtl] .flipped .square-53 {
  transform: translate(-300%, 200%);
}
html[dir=rtl] .square-63 {
  transform: translate(-500%, 500%);
}
html[dir=rtl] .flipped .square-63 {
  transform: translate(-200%, 200%);
}
html[dir=rtl] .square-73 {
  transform: translate(-600%, 500%);
}
html[dir=rtl] .flipped .square-73 {
  transform: translate(-100%, 200%);
}
html[dir=rtl] .square-83 {
  transform: translate(-700%, 500%);
}
html[dir=rtl] .flipped .square-83 {
  transform: translate(0%, 200%);
}
html[dir=rtl] .square-14 {
  transform: translate(0%, 400%);
}
html[dir=rtl] .flipped .square-14 {
  transform: translate(-700%, 300%);
}
html[dir=rtl] .square-24 {
  transform: translate(-100%, 400%);
}
html[dir=rtl] .flipped .square-24 {
  transform: translate(-600%, 300%);
}
html[dir=rtl] .square-34 {
  transform: translate(-200%, 400%);
}
html[dir=rtl] .flipped .square-34 {
  transform: translate(-500%, 300%);
}
html[dir=rtl] .square-44 {
  transform: translate(-300%, 400%);
}
html[dir=rtl] .flipped .square-44 {
  transform: translate(-400%, 300%);
}
html[dir=rtl] .square-54 {
  transform: translate(-400%, 400%);
}
html[dir=rtl] .flipped .square-54 {
  transform: translate(-300%, 300%);
}
html[dir=rtl] .square-64 {
  transform: translate(-500%, 400%);
}
html[dir=rtl] .flipped .square-64 {
  transform: translate(-200%, 300%);
}
html[dir=rtl] .square-74 {
  transform: translate(-600%, 400%);
}
html[dir=rtl] .flipped .square-74 {
  transform: translate(-100%, 300%);
}
html[dir=rtl] .square-84 {
  transform: translate(-700%, 400%);
}
html[dir=rtl] .flipped .square-84 {
  transform: translate(0%, 300%);
}
html[dir=rtl] .square-15 {
  transform: translate(0%, 300%);
}
html[dir=rtl] .flipped .square-15 {
  transform: translate(-700%, 400%);
}
html[dir=rtl] .square-25 {
  transform: translate(-100%, 300%);
}
html[dir=rtl] .flipped .square-25 {
  transform: translate(-600%, 400%);
}
html[dir=rtl] .square-35 {
  transform: translate(-200%, 300%);
}
html[dir=rtl] .flipped .square-35 {
  transform: translate(-500%, 400%);
}
html[dir=rtl] .square-45 {
  transform: translate(-300%, 300%);
}
html[dir=rtl] .flipped .square-45 {
  transform: translate(-400%, 400%);
}
html[dir=rtl] .square-55 {
  transform: translate(-400%, 300%);
}
html[dir=rtl] .flipped .square-55 {
  transform: translate(-300%, 400%);
}
html[dir=rtl] .square-65 {
  transform: translate(-500%, 300%);
}
html[dir=rtl] .flipped .square-65 {
  transform: translate(-200%, 400%);
}
html[dir=rtl] .square-75 {
  transform: translate(-600%, 300%);
}
html[dir=rtl] .flipped .square-75 {
  transform: translate(-100%, 400%);
}
html[dir=rtl] .square-85 {
  transform: translate(-700%, 300%);
}
html[dir=rtl] .flipped .square-85 {
  transform: translate(0%, 400%);
}
html[dir=rtl] .square-16 {
  transform: translate(0%, 200%);
}
html[dir=rtl] .flipped .square-16 {
  transform: translate(-700%, 500%);
}
html[dir=rtl] .square-26 {
  transform: translate(-100%, 200%);
}
html[dir=rtl] .flipped .square-26 {
  transform: translate(-600%, 500%);
}
html[dir=rtl] .square-36 {
  transform: translate(-200%, 200%);
}
html[dir=rtl] .flipped .square-36 {
  transform: translate(-500%, 500%);
}
html[dir=rtl] .square-46 {
  transform: translate(-300%, 200%);
}
html[dir=rtl] .flipped .square-46 {
  transform: translate(-400%, 500%);
}
html[dir=rtl] .square-56 {
  transform: translate(-400%, 200%);
}
html[dir=rtl] .flipped .square-56 {
  transform: translate(-300%, 500%);
}
html[dir=rtl] .square-66 {
  transform: translate(-500%, 200%);
}
html[dir=rtl] .flipped .square-66 {
  transform: translate(-200%, 500%);
}
html[dir=rtl] .square-76 {
  transform: translate(-600%, 200%);
}
html[dir=rtl] .flipped .square-76 {
  transform: translate(-100%, 500%);
}
html[dir=rtl] .square-86 {
  transform: translate(-700%, 200%);
}
html[dir=rtl] .flipped .square-86 {
  transform: translate(0%, 500%);
}
html[dir=rtl] .square-17 {
  transform: translate(0%, 100%);
}
html[dir=rtl] .flipped .square-17 {
  transform: translate(-700%, 600%);
}
html[dir=rtl] .square-27 {
  transform: translate(-100%, 100%);
}
html[dir=rtl] .flipped .square-27 {
  transform: translate(-600%, 600%);
}
html[dir=rtl] .square-37 {
  transform: translate(-200%, 100%);
}
html[dir=rtl] .flipped .square-37 {
  transform: translate(-500%, 600%);
}
html[dir=rtl] .square-47 {
  transform: translate(-300%, 100%);
}
html[dir=rtl] .flipped .square-47 {
  transform: translate(-400%, 600%);
}
html[dir=rtl] .square-57 {
  transform: translate(-400%, 100%);
}
html[dir=rtl] .flipped .square-57 {
  transform: translate(-300%, 600%);
}
html[dir=rtl] .square-67 {
  transform: translate(-500%, 100%);
}
html[dir=rtl] .flipped .square-67 {
  transform: translate(-200%, 600%);
}
html[dir=rtl] .square-77 {
  transform: translate(-600%, 100%);
}
html[dir=rtl] .flipped .square-77 {
  transform: translate(-100%, 600%);
}
html[dir=rtl] .square-87 {
  transform: translate(-700%, 100%);
}
html[dir=rtl] .flipped .square-87 {
  transform: translate(0%, 600%);
}
html[dir=rtl] .square-18 {
  transform: translate(0%, 0%);
}
html[dir=rtl] .flipped .square-18 {
  transform: translate(-700%, 700%);
}
html[dir=rtl] .square-28 {
  transform: translate(-100%, 0%);
}
html[dir=rtl] .flipped .square-28 {
  transform: translate(-600%, 700%);
}
html[dir=rtl] .square-38 {
  transform: translate(-200%, 0%);
}
html[dir=rtl] .flipped .square-38 {
  transform: translate(-500%, 700%);
}
html[dir=rtl] .square-48 {
  transform: translate(-300%, 0%);
}
html[dir=rtl] .flipped .square-48 {
  transform: translate(-400%, 700%);
}
html[dir=rtl] .square-58 {
  transform: translate(-400%, 0%);
}
html[dir=rtl] .flipped .square-58 {
  transform: translate(-300%, 700%);
}
html[dir=rtl] .square-68 {
  transform: translate(-500%, 0%);
}
html[dir=rtl] .flipped .square-68 {
  transform: translate(-200%, 700%);
}
html[dir=rtl] .square-78 {
  transform: translate(-600%, 0%);
}
html[dir=rtl] .flipped .square-78 {
  transform: translate(-100%, 700%);
}
html[dir=rtl] .square-88 {
  transform: translate(-700%, 0%);
}
html[dir=rtl] .flipped .square-88 {
  transform: translate(0%, 700%);
}`,Fn=`.board.analysis-overlay:before {
  background: rgba(255, 255, 255, 0.2);
  content: " ";
  height: 100%;
  position: absolute;
  width: 100%;
}`;function en({board:e}){const{classList:t}=e.el;return{addAnalysisOverlay:r,removeAnalysisOverlay:o};function r(){t.add("analysis-overlay")}function o(){t.remove("analysis-overlay")}}var Mn=`.board .arrows {
  height: 100%;
  left: 0;
  pointer-events: none;
  position: absolute;
  top: 0;
  width: 100%;
}

.board.pseudo3d .arrows {
  z-index: 9;
}

.board.flipped .arrows {
  transform: scale(-1, -1);
}`;const j={a1:{x:6.25,y:93.75},a2:{x:6.25,y:81.25},a3:{x:6.25,y:68.75},a4:{x:6.25,y:56.25},a5:{x:6.25,y:43.75},a6:{x:6.25,y:31.25},a7:{x:6.25,y:18.75},a8:{x:6.25,y:6.25},b1:{x:18.75,y:93.75},b2:{x:18.75,y:81.25},b3:{x:18.75,y:68.75},b4:{x:18.75,y:56.25},b5:{x:18.75,y:43.75},b6:{x:18.75,y:31.25},b7:{x:18.75,y:18.75},b8:{x:18.75,y:6.25},c1:{x:31.25,y:93.75},c2:{x:31.25,y:81.25},c3:{x:31.25,y:68.75},c4:{x:31.25,y:56.25},c5:{x:31.25,y:43.75},c6:{x:31.25,y:31.25},c7:{x:31.25,y:18.75},c8:{x:31.25,y:6.25},d1:{x:43.75,y:93.75},d2:{x:43.75,y:81.25},d3:{x:43.75,y:68.75},d4:{x:43.75,y:56.25},d5:{x:43.75,y:43.75},d6:{x:43.75,y:31.25},d7:{x:43.75,y:18.75},d8:{x:43.75,y:6.25},e1:{x:56.25,y:93.75},e2:{x:56.25,y:81.25},e3:{x:56.25,y:68.75},e4:{x:56.25,y:56.25},e5:{x:56.25,y:43.75},e6:{x:56.25,y:31.25},e7:{x:56.25,y:18.75},e8:{x:56.25,y:6.25},f1:{x:68.75,y:93.75},f2:{x:68.75,y:81.25},f3:{x:68.75,y:68.75},f4:{x:68.75,y:56.25},f5:{x:68.75,y:43.75},f6:{x:68.75,y:31.25},f7:{x:68.75,y:18.75},f8:{x:68.75,y:6.25},g1:{x:81.25,y:93.75},g2:{x:81.25,y:81.25},g3:{x:81.25,y:68.75},g4:{x:81.25,y:56.25},g5:{x:81.25,y:43.75},g6:{x:81.25,y:31.25},g7:{x:81.25,y:18.75},g8:{x:81.25,y:6.25},h1:{x:93.75,y:93.75},h2:{x:93.75,y:81.25},h3:{x:93.75,y:68.75},h4:{x:93.75,y:56.25},h5:{x:93.75,y:43.75},h6:{x:93.75,y:31.25},h7:{x:93.75,y:18.75},h8:{x:93.75,y:6.25}},T=n.X.WIDTH/2,D=n.X.HEAD_HEIGHT,_=n.X.TAIL_PADDING,R=n.X.HEAD_WIDTH/2;function tn({from:e,to:t}){const r=(0,n.N)({from:e,to:t});return`
    ${e.x-T} ${e.y+_},
    ${e.x-T} ${e.y+r-D},
    ${e.x-R} ${e.y+r-D},
    ${e.x} ${e.y+r},
    ${e.x+R} ${e.y+r-D},
    ${e.x+T} ${e.y+r-D},
    ${e.x+T} ${e.y+_}
  `.trim()}function rn({from:e,polygon:t,to:r}){const o=(0,n.S)({from:e,to:r});return t.setAttribute("transform",`rotate(${o} ${e.x} ${e.y})`),t.setAttribute("points",tn({from:e,to:r})),t}const k=n.X.WIDTH/2,H=n.X.HEAD_HEIGHT,X=n.X.TAIL_PADDING,G=n.X.HEAD_WIDTH/2;function on({from:e}){return`
    ${e.x-k} ${e.y+X},
    ${e.x-k} ${e.y+25+k},
    ${e.x+12.5-H} ${e.y+25+k},
    ${e.x+12.5-H} ${e.y+25+G},
    ${e.x+12.5} ${e.y+25},
    ${e.x+12.5-H} ${e.y+25-G},
    ${e.x+12.5-H} ${e.y+25-k},
    ${e.x+k} ${e.y+25-k},
    ${e.x+k} ${e.y+X}
  `.trim()}function an({from:e,polygon:t,slope:r,to:o}){let d=`rotate(${(0,n.Q)({from:e,slope:r,to:o})} ${e.x} ${e.y})`;return n.W.includes(r)&&(d+=` scale(-1, 1) translate(-${2*e.x}, 0)`),t.setAttribute("transform",d),t.setAttribute("points",on({from:e})),t}function sn(e,t){if(!e.key)return;const{color:r,from:o,opacity:d,to:u}=e.data,i=document.createElementNS("http://www.w3.org/2000/svg","polygon");i.setAttribute("id",`arrow-${o}${u}`),i.setAttribute("data-arrow",`${o}${u}`),i.setAttribute("class","arrow");const{arrowColors:l}=t.options;i.style.fill=(0,n.q)(r,l),d&&(i.style.opacity=String(d));const f=j[o],m=j[u];if(!f||!m)return;const x=(0,n.N)({from:f,to:m}),s=(0,n.O)({from:f,to:m});return n.cs.includes(s)&&x===n.ct?an({from:f,polygon:i,slope:s,to:m}):rn({from:f,polygon:i,to:m})}function dn({board:e}){const t=document.createElementNS("http://www.w3.org/2000/svg","svg");t.setAttribute("viewBox","0 0 100 100"),t.classList.add("arrows"),e.addToDom({el:t,type:n.cu.Arrows});const r=new Map;return{addArrows:o,removeArrows:d};function o(u,i){u.forEach(l=>{const f=sn(l,i);f&&(t.appendChild(f),r.set(l.key,f))})}function d(u){u.forEach(i=>{const{key:l}=i,f=r.get(l);f&&(t.removeChild(f),r.delete(l))})}}function ln({boardStyles:e,options:t,pieceStyles:r}){return f(t),{destroy:o,getStyleEl:d,getPieceStyles:()=>r,updateStyles:f};function o(){const m=document.getElementById(u());!m||!m.parentNode||m.parentNode.removeChild(m)}function d(){return document.getElementById(u())}function u(){return t.useSharedStyleTag?"board-styles-shared":`board-styles-${t.id}`}function i(m,x=!1){const{boardStyle:s,id:a,pieceStyle:c}=m,{path:g,format:h,isPseudo3d:q}=r[c],p=(0,n.a3)(g),y=e[s],w=q?"::after":"",v=x?n.cv.reduce((P,b)=>`${P}#board-${a} .piece.${b}, #board-${a} .promotion-piece.${b} {
            background-image: url('${n.a4[b]}');
          }`,""):n.cv.reduce((P,b)=>`${P}#board-${a} .piece.${b}${w}, #board-${a} .promotion-piece.${b}${w} {
            background-image: url('${p}/${b}.${h}');
          }`,"");return`
      #board-${a}, .fade-in-overlay {
        background-image: url('${(0,n.a2)(y[2])}');
      }
      .coordinate-light {
        fill: ${y[0]};
      }
      .coordinate-dark {
        fill: ${y[1]};
      }
      .highlight {
        background-color: ${y[4]};
      }
    `+v}function l(m,x=!1){const{id:s,themeAssets:a}=m;if(!a)return i(m,x);const c=a.config.perspective===n.a0.Perspective.PSEUDO_3D?"::after":"",g=Object.keys(a.pieces.assets).reduce((q,p)=>`${q}#board-${s} .piece.${p}${c}, #board-${s} .promotion-piece.${p}${c} {
        background-image: url('${a.pieces.assets[p]}');
      }`,"");return`
      #board-${s}, .fade-in-overlay {
        background-image: url('${a.board.assets.background}');
      }
      .coordinate-light {
        fill: ${a.board.config.lightSquareCoordinateHex};
      }
      .coordinate-dark {
        fill: ${a.board.config.darkSquareCoordinateHex};
      }
      .highlight {
        background-color: ${a.board.config.highlightSquareHex};
      }
    `+g}function f(m,x=!1){const s=u();let a=d();if(!a){a=document.createElement("style"),a.type="text/css",a.id=s;const g=document.head;g&&g.appendChild(a)}const c=l(m,x);a.innerHTML!==c&&(a.innerHTML=c)}}function un({boardStyles:e,el:t,options:r,pieceStyles:o}){const d=ln({boardStyles:e,options:r,pieceStyles:o});return{destroy:u,updateBoardImage:i,updatePieceBaseImage:l,togglePseudo3d:f,getPieceStyles:d.getPieceStyles};function u(){d.destroy()}function i(m){d.updateStyles((0,n.K)(m.options))}function l(m,x=!1){f(m.options),d.updateStyles((0,n.K)(m.options),x)}function f(m){const x=m.themeAssets?m.themeAssets.config.perspective===n.a0.Perspective.PSEUDO_3D:o[r.pieceStyle].isPseudo3d;t.classList.toggle(n.aX.Pseudo3d,!!x)}}function pn({el:e,options:t,testElement:r=n.j.Board}){var o;const d={[n.cu.Coordinates]:document.createComment("/Coordinates"),[n.cu.Squares]:document.createComment("/Squares"),[n.cu.BlinkingHighlights]:document.createComment("/Blinking Highlights"),[n.cu.Effects]:document.createComment("/Effects"),[n.cu.HoverSquare]:document.createComment("/Hover Square"),[n.cu.Pieces]:document.createComment("/Pieces"),[n.cu.MoveHints]:document.createComment("/MoveHints"),[n.cu.CaptureHints]:document.createComment("/Capture Hints"),[n.cu.Arrows]:document.createComment("/Arrows"),[n.cu.PromotionWindow]:document.createComment("/Promotion Window"),[n.cu.FadeSetup]:document.createComment("/Fade Setup")};Object.values(d).forEach(h=>e.appendChild(h));let u=(0,n.cw)();return(o=u.resolve)==null||o.call(u,!0),t.test&&(0,n.s)(e,{[n.T.Element]:r}),{addToDom:i,animationComplete:a,el:e,flipBoard:l,setBoardEnabled:x,isAnimating:s,isFlipped:c,placeholders:d,setAnimatingStatus:g,reset:f};function i({type:h,el:q,insertAfter:p=!1}){p?e.insertBefore(q,d[h].nextSibling):e.insertBefore(q,d[h])}function l(h,q){if(e.classList.toggle(n.aX.Flipped,h),q?.options.allowMarkings){const p=q.api.markings.getAllWhere({types:[n.cx]});p.length>0&&m(p,q.renderer)}}function f(){e.innerHTML="",e.classList.remove(n.aX.Flipped)}function m(h,q){h.forEach(p=>{var y;const{data:{square:w},key:v}=p,z=(y=q?.getEffectElements)==null?void 0:y.call(q).get(v);if(z){if(z.classList.contains("tuck-right")||z.classList.contains("tuck-top")){z.classList.remove("tuck-right"),z.classList.remove("tuck-top");return}(0,n.cy)(w)&&(0,n.cz)({square:w,isFlipped:e.classList.contains("flipped"),effectEl:z})}})}function x(){}function s(){return!!e.dataset.testAnimating}function a(h){return[n.A.All,n.A.Move].includes(h)?u.promise:Promise.resolve(!0)}function c(){return t.flipped}function g(h){var q;h?(e.dataset.testAnimating="true",u=(0,n.cw)()):(delete e.dataset.testAnimating,(q=u.resolve)==null||q.call(u,!0))}}function fn(e){const t=["8","7","6","5","4","3","2","1","a","b","c","d","e","f","g","h"];return e?[...t.slice(0,8).reverse(),...t.slice(-8).reverse()]:t}function cn(e){return e===n.B.CoordinatesPositions.Outside?[{color:"grey",fontSize:3.1,x:2,y:3.5},{color:"grey",fontSize:3.1,x:2,y:16},{color:"grey",fontSize:3.1,x:2,y:28.5},{color:"grey",fontSize:3.1,x:2,y:41},{color:"grey",fontSize:3.1,x:2,y:53.5},{color:"grey",fontSize:3.1,x:2,y:66},{color:"grey",fontSize:3.1,x:2,y:78.5},{color:"grey",fontSize:3.1,x:2,y:91},{color:"grey",fontSize:3.1,x:10.35,y:99.25},{color:"grey",fontSize:3.1,x:22.85,y:99.25},{color:"grey",fontSize:3.1,x:35.35,y:99.25},{color:"grey",fontSize:3.1,x:47.85,y:99.25},{color:"grey",fontSize:3.1,x:60.35,y:99.25},{color:"grey",fontSize:3.1,x:72.85,y:99.25},{color:"grey",fontSize:3.1,x:85.35,y:99.25},{color:"grey",fontSize:3.1,x:97.85,y:99.25}]:[{color:"light",fontSize:2.8,x:.75,y:3.5},{color:"dark",fontSize:2.8,x:.75,y:15.75},{color:"light",fontSize:2.8,x:.75,y:28.25},{color:"dark",fontSize:2.8,x:.75,y:40.75},{color:"light",fontSize:2.8,x:.75,y:53.25},{color:"dark",fontSize:2.8,x:.75,y:65.75},{color:"light",fontSize:2.8,x:.75,y:78.25},{color:"dark",fontSize:2.8,x:.75,y:90.75},{color:"dark",fontSize:2.8,x:10,y:99},{color:"light",fontSize:2.8,x:22.5,y:99},{color:"dark",fontSize:2.8,x:35,y:99},{color:"light",fontSize:2.8,x:47.5,y:99},{color:"dark",fontSize:2.8,x:60,y:99},{color:"light",fontSize:2.8,x:72.5,y:99},{color:"dark",fontSize:2.8,x:85,y:99},{color:"light",fontSize:2.8,x:97.5,y:99}]}function mn(e,t){const r=fn(t);return cn(e).map((d,u)=>C(S({},d),{text:r[u]})).map(d=>`<text 
          x="${d.x}" 
          y="${d.y}" 
          ${d.fontSize?`font-size="${d.fontSize}"`:""} 
          class="coordinate-${d.color}">${d.text}</text>`).join("")}function qn({board:e,options:t}){return{setCoordinates:o};function r(u,i){const l=document.createElementNS("http://www.w3.org/2000/svg","svg");l.setAttribute("viewBox","0 0 100 100"),l.classList.add("coordinates"),u===n.B.CoordinatesPositions.Outside&&l.classList.add("outside"),t.test&&(0,n.s)(l,{[n.T.Element]:n.j.Coordinates,[n.T.Flipped]:i.toString(),[n.T.Position]:u}),l.innerHTML=mn(u,i),e.addToDom({el:l,type:n.cu.Coordinates})}function o({flipped:u,position:i}){d(),i!==n.B.CoordinatesPositions.Off&&r(i,u)}function d(){const u=e.el.querySelector(".coordinates");u&&u.parentNode.removeChild(u)}}var Nn=`@-webkit-keyframes fadeOut {
  from {
    opacity: 1;
  }
  to {
    opacity: 0;
  }
}
@keyframes fadeOut {
  from {
    opacity: 1;
  }
  to {
    opacity: 0;
  }
}
.fade-in-overlay {
  background-size: 100%;
  display: none;
  height: 100%;
  left: 0;
  /**
   * Honor the cursor behavior of the elements underneath this during fade in
   */
  pointer-events: none;
  position: absolute;
  top: 0;
  touch-action: none;
  width: 100%;
  /*
   * When picking up a piece while fade-in, we want the picked-up piece to not be any
   * more opaque than the siblings pieces. We ensure that by keeping this overlay
   * above all the pieces.
   */
  z-index: 3;
}
.fade-in-overlay.animate {
  -webkit-animation-name: fadeOut;
  animation-name: fadeOut;
  display: block;
}`;function hn({board:e}){let t;return{fadeSetup:u};function r(i){t=document.createElement("div"),t.classList.add("fade-in-overlay"),e.addToDom({el:t,type:n.cu.FadeSetup}),t.addEventListener("animationend",o),i.test&&(0,n.s)(t,{[n.T.Element]:n.j.FadeInOverlay})}function o(){t&&(t.style.animationDuration="",t.classList.remove("animate"))}function d(){t&&t.remove(),t=void 0}function u({options:i}){if(i.fadeSetup===0){t&&d();return}t||r(i),t&&(t.style.animationDuration=`${i.fadeSetup/1e3}s`,t.classList.add("animate"))}}function gn({board:e,options:t}){const r=u();return{hideHoverSquare:d,showHoverSquare:o};function o(i){r.style.visibility="",t.test&&(0,n.s)(r,{[n.T.Element]:n.j.HoverSquare}),(0,n.cA)({el:r,square:i})}function d(){r&&(r.style.visibility="hidden")}function u(){const i=document.createElement("div"),l=document.createElementNS("http://www.w3.org/2000/svg","svg");return l.setAttribute("viewBox","0 0 100 100"),l.innerHTML='<rect x="0" y="0" width="100" height="100" stroke="rgba(255, 255, 255, 0.65)" stroke-width="10" fill="none"/>',i.append(l),i.classList.add("hover-square"),i.style.visibility="hidden",e.addToDom({el:i,type:n.cu.HoverSquare}),t.test&&(0,n.s)(i,{[n.T.Element]:n.j.HoverSquare}),i}}function yn({board:e}){const t={};return{addCaptureHints:o,addMoveHints:r,removeHints:d};function r(l){l.forEach(f=>{t[f]||(t[f]=u(f),e.addToDom({el:t[f],type:n.cu.MoveHints}))})}function o(l){l.forEach(f=>{t[f]&&i(f),t[f]=u(f,{isPotentialCapture:!0}),e.addToDom({el:t[f],type:n.cu.CaptureHints}),t[f].style.borderWidth=`${t[f].clientWidth*.1}px`})}function d(l){l.forEach(i)}function u(l,f={}){const m=document.createElement("div");return(0,n.s)(m,{[n.T.Element]:f.isPotentialCapture?n.j.PotentialCapture:n.j.Hint}),m.classList.add(f.isPotentialCapture?"capture-hint":"hint"),(0,n.cA)({el:m,square:l}),m}function i(l){if(!t[l])return;t[l].parentNode.removeChild(t[l]),delete t[l]}}function xn(e,t){return{data:{color:t,interval:500,opacity:.5,square:e,times:3},key:e,type:n.cB}}function wn({animation:e,numSteps:t}){return Array(t).fill(void 0).map((r,o,d)=>{let u,l=2.86-2.86*((o+1)/t);return l=l>1?1:l,o===d.length-1&&e.callback&&(u=e.callback),{callback:u,el:e.el,style:{opacity:l.toString()}}})}function bn({animation:e,isFlipped:t=!1,numSteps:r}){const{el:o,from:d,to:u}=e;if(!d||!u)return[];const i=O(d,t),l=O(u,t);if(!i||!l)return[];const f=(l.x-i.x)/r,m=(l.y-i.y)/r;return Array(r).fill(void 0).map((x,s)=>{const a=s===r-1,c=a?l:{x:i.x+f*(s+1),y:i.y+m*(s+1)};return{el:o,style:{transform:a?"":`translate(${c.x}%, ${c.y}%)`,zIndex:a?"":"10"}}})}const zn=16;function Sn(e){let t;const r=[],o={[$.FadeOut]:wn,[$.Slide]:bn};return{add:d,flush:u,run:i};function d(l,f){const{animationType:m,flipped:x}=f,s=Math.max(Math.floor(vn(m)/zn),1);l.map(c=>o[c.type]({animation:c,isFlipped:x||!1,numSteps:s})).forEach(c=>{if(c.length>r.length){const g=Array(c.length-r.length).fill([]);r.unshift(...g)}c.forEach((g,h,q)=>{const p=q.length-h;r[r.length-p]=[...r[r.length-p],g]})})}function u(){if(r.length===0){t=!0;return}r.splice(0,r.length-1),i()}function i(){t=r.length<2;const l=r.shift();l&&(e.isAnimating()||e.setAnimatingStatus(!0),l.forEach(f=>{Object.entries(f.style).forEach(([m,x])=>{f.el.style[m]=x}),f.callback&&f.callback()}),r.length===0&&e.setAnimatingStatus(!1),t||requestAnimationFrame(()=>{i()}))}}function vn(e){switch(e){case n.B.Animation.Types.Slow:return n.B.Animation.Speeds.Slow;case n.B.Animation.Types.Fast:return n.B.Animation.Speeds.Fast;case n.B.Animation.Types.None:return 0;default:return n.B.Animation.Speeds.Default}}function Pn({details:e,el:t,emitter:r,test:o}){let d;const u=S({},e);return t.classList.add("piece"),r.emit(E.Created,S({},u)),f(u.color,u.type),x(u.square),o&&(0,n.s)(t,{[n.T.Element]:n.j.Piece}),{el:t,getDetails:l,setDetails:f,setDraggingState:m,setPositionByCoords:s,setPositionBySquare:x};function i(a,c){return`${(0,n.a1)(a)}${c}`}function l(){return e}function f(a,c){t.classList.remove(d),d=i(a,c),t.classList.add(d),e.type=c,e.color=a,r.emit(E.DetailsSet,C(S({},e),{shortString:d})),o&&(0,n.s)(t,{[n.T.Type]:c,[n.T.Color]:(0,n.a1)(a),[n.T.ShortString]:a===n.G.ColorsAsNumbers.Black?c:c.toUpperCase()})}function m(a){if(a){t.classList.add("dragging"),r.emit(E.DragStarted,S({},e)),o&&(0,n.s)(t,{[n.T.Dragging]:"true"});return}o&&(0,n.s)(t,{[n.T.Dragging]:void 0}),t.classList.remove("dragging"),r.emit(E.DragEnded,S({},e))}function x(a,c){if(o){const g=(0,n.g)(a);if(!g)return;g&&!c&&(0,n.s)(t,{[n.T.File]:g.file.toString(),[n.T.Rank]:g.rank.toString(),[n.T.Square]:a})}(0,n.cA)({el:t,square:a}),r.emit(E.PositionSetBySquare,S({},e))}function s(a){a&&(t.style.transform=`translate(${a.x}%, ${a.y}%)`)}}function En({board:e,emitter:t,options:r}){const o=M({insertBefore:e.placeholders.pieces,startingCount:32}),d=Sn(e);let u;const i=(0,n.cC)();return{animations:d,create:l,destroy:f,get:m,getDraggingSquare:x,move:s,remove:a,setDraggingState:c,setPositionByCoords:g,setPositionBySquare:h,suspendOverSquare:q};function l(p){const y=Pn({details:p,el:o.get(),emitter:t,test:r.test});return i.set(p.square,y),t.emit(E.PieceShown,S({},i.get(p.square))),y}function f(){o.destroy()}function m(p,y=!0){if(!p)return i;if(!i.isDefined(p)&&y)throw new n.C({code:n.E.ElementNotFound,data:{square:p},message:"Piece does not exist."});return i.isDefined(p)?i.get(p):void 0}function x(){return u}function s(p,y){const w=Array.isArray(p)?p:[p],v=w.filter(Boolean).map(b=>b.kingTo||b.to),z=w.filter(Boolean).map(b=>m(b.from)),P=[];w.forEach((b,L)=>{if(!b)throw new n.C({code:n.E.ElementNotFound,message:"Move object does not exist."});const A=b.kingTo||b.to;A&&(z[L].setPositionBySquare(A),b.animate&&P.push({el:z[L].el,from:b.from,to:A,type:$.Slide}),i.set(A,z[L]),v.includes(b.from)||i.deleteItem(b.from),b.promotion&&i.get(A).setDetails(b.color,b.promotion))}),P.length&&d.add(P,y)}function a({animate:p,options:y,squares:w}){const v=[];w.forEach(z=>{if(!i.isDefined(z))return;const P=m(z).el;p?v.push({callback:b,el:m(z).el,type:$.FadeOut}):b(),i.deleteItem(z);function b(){if(!P)throw new n.C({code:n.E.ElementNotFound,data:{square:z},message:"Piece does not exist. Cannot remove."});o.put(P),t.emit(E.PieceHidden,z)}}),v.length&&d.add(v,y),u&&!i.isDefined(u)&&(u=void 0)}function c(p,y){m(p).setDraggingState(y),u=y?p:void 0}function g(p,y){const w=m(p),v=x();v&&c(v,!1),w.setPositionByCoords(y)}function h(p,y){const w=m(p);y!==p&&(i.set(y,i.get(p)),i.deleteItem(p)),w.setPositionBySquare(y)}function q(p,y){m(p).setPositionBySquare(y,!0)}}function kn({board:e,emitter:t,options:r}){const o=En({board:e,emitter:t,options:r});return{destroy:o.destroy,dragPiece:d,dropPiece:u,illegalMove:i,loadPieces:f,makeMove:l,removePiece:m,undoMove:x};function d(s){s&&(s.toSquare?o.suspendOverSquare(s.square,s.toSquare):s.coords&&o.setPositionByCoords(s.square,s.coords),o.setDraggingState(s.square,!0))}function u(){const s=o.getDraggingSquare();s&&(o.setPositionBySquare(s,s),o.setDraggingState(s,!1))}function i(s,a){if(s){const{renderer:c}=a;c?.blinkHighlights([xn(s,r.checkBlinkingSquareColor)],a)}}function l(s,a){if(o.animations.flush(),s.drop){o.create({color:s.color,square:s.kingTo||s.to,type:s.piece});return}s.EPCapturedSquare?o.remove({animate:s.animate,options:a.options,squares:[s.EPCapturedSquare]}):o.get(s.to,!1)&&!(0,n.n)(s)&&o.remove({animate:s.animate,options:a.options,squares:[s.to]});const c=[s,s.rookMove].filter(Boolean);o.move(c,a.options),o.animations.run()}function f(s,a){const c=s.pieces;o.animations.flush();const g=c.keys(),h=o.get().keys().filter(q=>!g.includes(q));o.remove({options:a.options,squares:h}),c.keys().forEach(q=>{const p=c.get(q),y=o.get(q,!1);if(!y){o.create(p);return}const{type:w,color:v}=y.getDetails();(w!==p.type||v!==p.color)&&y.setDetails(p.color,p.type)}),o.animations.run()}function m(s,a){o.remove({options:a.options,squares:[s]})}function x(s,a){if(o.animations.flush(),s.drop){m(s.to,a);return}const{movedPieces:c,restoredPiece:g,promotedSquare:h}=(0,n.p)(s);if(o.move(c,a.options),g&&o.create(g),h){const q=o.get(h),{color:p}=q.getDetails();q.setDetails(p,n.G.Piece.Types.Pawn)}o.animations.run()}}function $n({board:e,options:t}){let r,o;const d={b:void 0,n:void 0,q:void 0,r:void 0};return{closePromotionWindow:x,openPromotionWindow:m};function u(){r=document.createElement("div"),r.classList.add("promotion-window"),t.test&&(0,n.s)(r,{[n.T.Element]:n.j.PromotionWindow}),e.addToDom({el:r,type:n.cu.PromotionWindow})}function i(h){o=document.createElement("i"),o.className="close-button icon-font-chess x",t.test&&(0,n.s)(o,{[n.T.Element]:n.j.PromotionCloseButton}),r.appendChild(o),o.addEventListener(n.P,q=>{q.stopPropagation(),h((0,n.u)(n.w.BoardEvents.PromotionAreaClosePointerdown))})}function l(h){Object.keys(d).forEach(q=>{const p=document.createElement("div");p.addEventListener(n.P,y=>{if(y.stopPropagation(),(0,n.z)(y)){h((0,n.u)(n.w.UserEvents.PointerdownRight));return}h((0,n.u)(n.w.BoardEvents.PromotionPiecePointerdown,{piece:q}))}),p.classList.add("promotion-piece"),d[q]=p,t.test&&(0,n.s)(d[q],{[n.T.Element]:n.j.PromotionPiece,[n.T.Type]:q}),r.appendChild(p)})}function f({flipped:h,promotionMove:q}){let p;h?p=q.color===n.G.ColorsAsNumbers.White?"bottom":"top":p=q.color===n.G.ColorsAsNumbers.White?"top":"bottom";const y=q.color,v=(h?"hgfedcba":"abcdefgh").indexOf(q.to.slice(0,1))+1;return{color:y,file:v,position:p}}function m(h,q){const{options:{flipped:p},run:y}=q,{color:w,file:v,position:z}=f({flipped:p,promotionMove:h});r||u(),o||i(y),d.q||l(y),a(z),c(v),s(w),t.test&&(0,n.s)(r,{[n.T.Color]:(0,n.a1)(w),[n.T.File]:v.toString(),[n.T.Position]:z}),g()}function x(){if(!r)throw new n.C({code:n.E.ElementNotFound,message:"Promotion window does not exist."});r.style.display="none"}function s(h){Object.keys(d).forEach(q=>{d[q].className=`promotion-piece ${(0,n.a1)(h)}${q}`,t.test&&(0,n.s)(d[q],{[n.T.Color]:(0,n.a1)(h)})})}function a(h){h==="top"?r.classList.add("top"):r.classList.remove("top")}function c(h){r.style.transform=`translateX(${(h-1)*100}%`}function g(){r.style.display=""}}function Cn({board:e,options:t}){const r=M({insertBefore:e.placeholders.squares,startingCount:3}),o=(0,n.cC)(),d=t.test;return{addHighlights:u,blinkHighlights:l,removeHighlights:f};function u(a){a.forEach(c=>{const{square:g}=c.data;if(o.isDefined(g)||!c)return;const h=m(g,c);o.set(g,h)})}function i({el:a,interval:c,opacity:g,times:h}){let q=0,p=!0;const y=setInterval(()=>{if(q+=1,q===h*2){if(clearInterval(y),a){const w=a.parentNode;w&&w.removeChild(a)}return}p?(a.style.opacity="0",p=!1):(a.style.opacity=g.toString(),p=!0)},c/2)}function l(a){a.forEach(c=>{const{color:g,interval:h,opacity:q,square:p,times:y}=c.data,w=m(p,c);e.addToDom({el:w,type:n.cu.BlinkingHighlights}),d&&(0,n.s)(w,{[n.T.Element]:n.j.BlinkingHighlight,[n.T.Square]:p,[n.T.Color]:g,[n.T.Interval]:h,[n.T.Opacity]:q,[n.T.Times]:y,[n.T.Type]:n.T.Blinking}),i({el:w,interval:h,opacity:q,times:y})})}function f(a){a.forEach(x)}function m(a,c){const g=r.get();return(0,n.s)(g,{[n.T.Element]:n.j.Highlight}),g.classList.add("highlight"),s(g,c),(0,n.cA)({el:g,square:a}),g}function x(a){const{square:c}=a.data;if(!o.isDefined(c))return;const g=o.get(c);r.put(g),o.deleteItem(c)}function s(a,c){if(!a)throw new n.C({code:n.E.ElementNotFound,data:{highlight:c},message:"Highlight does not exist."});const{data:{color:g,opacity:h}}=c,q=g||"",p=String(h);a.style.backgroundColor!==q&&(a.style.backgroundColor=q),a.style.opacity!==p&&(a.style.opacity=p)}}function An(e){let t=Math.max(e.x,-50);t=Math.min(t,750);let r=Math.max(e.y,-50);return r=Math.min(r,750),{x:t,y:r}}function Bn(e,t){let r=Math.max(e,1);r=Math.min(r,8);let o=Math.max(t,1);return o=Math.min(o,8),(0,n.f)({file:r,rank:o})}function Tn({el:e,event:t,flipped:r}){const o=e.getBoundingClientRect(),{x:d,y:u}=(0,n.x)(t),i=o.width/8,l={x:Math.round((d-o.left)%i),y:Math.round((u-o.top)%i)},f=Math.ceil((d-o.left)/i),m=Math.ceil((o.bottom-u)/i),x=r?9-f:f,s=r?9-m:m,a=(0,n.f)({file:x,rank:s}),c={x:(d-o.left-i/2)/o.width*800,y:(u-o.top-i/2)/o.height*800},g=An(c),h=Bn(x,s);return{coords:c,coordsInsideBoard:g,coordsInsideSquare:l,square:a,squareInsideBoard:h}}function Dn(e){const t={};return e.forEach(r=>{const o=r.className.split(" "),d=o.find(s=>s.startsWith("square-")),u=o.find(s=>s.startsWith("w")||s.startsWith("b"));if(!d||!u)return;const[i,l]=u,f=parseInt(d[d.length-2],10),m=parseInt(d[d.length-1],10),x=(0,n.f)({file:f,rank:m});x&&(t[x]={color:(0,n.cD)(i),type:l})}),t}function K({el:e,emitter:t=(0,V.H)(),options:r=(0,n.ce)(),boardStyles:o=n.cb,pieceStyles:d=n.cc,testElement:u}){const i=pn({el:e,options:r,testElement:u}),l={board:i,el:e,emitter:t,options:r},f=un(C(S({},l),{boardStyles:o,pieceStyles:d,options:(0,n.K)(r)})),m=kn(l);i.isFlipped()&&i.flipBoard(!0),f.togglePseudo3d(r);const x=C(S(C(S(S(S(S(S(S(S(S(S(S({},en(l)),dn(l)),f),qn(l)),hn(l)),gn(l)),yn(l)),Cn(l)),$n(l)),m),{animationComplete:i.animationComplete,areAssetsLoaded:()=>!0,createRenderer:K,destroy:s}),t),{extendRenderer:a,flipBoard:i.flipBoard,setBoardEnabled:i.setBoardEnabled,getCoordsFromSquare:O,getPieces:c,getPointerPosition:Tn,getRendererOptions:()=>({boardStyles:o,el:e,options:r,pieceStyles:d,emitter:t}),getBoardContainerAspectRatio:()=>1,isAnimating:i.isAnimating,name:n.bD.Types.Default,resize:g});return x;function s(){f.destroy(),m.destroy(),i.reset()}function a(h){Object.assign(x,h(l))}function c(){const h=Array.from(e.querySelectorAll(".piece"));return Dn(h)}function g(){}}}}]);
