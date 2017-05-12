CSS/SASS
========

Architecture / folders organisation
-----------------------------------
Custom 7-1 Pattern is used.

_cf:_ https://sass-guidelin.es/#the-7-1-pattern


Declaration order
-----------------
```css
.selector1,
.selector2 {
    /* Positioning */
    position: absolute;
    z-index: 10;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    
    /* Display & Box Model */
    display: inline-block;
    overflow: hidden;
    box-sizing: border-box;
    width: 100px;
    height: 100px;
    padding: 10px;
    border: 10px solid #333;
    margin: 10px;
    
    /* Other */
    background: #000;
    color: #fff;
    font-family: sans-serif;
    font-size: 16px;
    text-align: right;
}
```

_cf:_ https://github.com/necolas/idiomatic-css


Good to read
------------

* https://sass-guidelin.es/
* https://scotch.io/tutorials/aesthetic-sass-1-architecture-and-style-organization
