import{_ as o}from"./ProductCard-8f672aaf.js";import{a as e,c as s,F as c,f as a,k as d,e as l}from"./app-af608028.js";import"./getProductImage-92473f05.js";const n={class:"grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"},i={key:1,class:"text-center col-span-full"},u=l("p",{class:"text-xl font-semibold"},"No products found",-1),p=[u],h={__name:"ProductGallery",props:{products:{type:Array,required:!0}},setup(t){return(_,m)=>(e(),s("div",n,[t.products&&t.products.length>0?(e(!0),s(c,{key:0},a(t.products,r=>(e(),d(o,{key:r.id,product:r},null,8,["product"]))),128)):(e(),s("div",i,p))]))}};export{h as default};
