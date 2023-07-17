import{A as x,a as c,k,w as b,c as P,B as h,e as i,C as f,l as v,F as y,f as w,t as R,n as A}from"./app-af608028.js";const C={emits:["pagination-change-page"],props:{data:{type:Object,default:()=>{}},limit:{type:Number,default:0},keepLength:{type:Boolean,default:!1}},computed:{isApiResource(){return!!this.data.meta},currentPage(){return this.isApiResource?this.data.meta.current_page:this.data.current_page},firstPageUrl(){return this.isApiResource?this.data.links.first:null},from(){return this.isApiResource?this.data.meta.from:this.data.from},lastPage(){return this.isApiResource?this.data.meta.last_page:this.data.last_page},lastPageUrl(){return this.isApiResource?this.data.links.last:null},nextPageUrl(){return this.isApiResource?this.data.links.next:this.data.next_page_url},perPage(){return this.isApiResource?this.data.meta.per_page:this.data.per_page},prevPageUrl(){return this.isApiResource?this.data.links.prev:this.data.prev_page_url},to(){return this.isApiResource?this.data.meta.to:this.data.to},total(){return this.isApiResource?this.data.meta.total:this.data.total},pageRange(){if(this.limit===-1)return 0;if(this.limit===0)return this.lastPage;for(var e=this.currentPage,r=this.keepLength,t=this.lastPage,n=this.limit,u=e-n,m=e+n,g=(n+2)*2,a=(n+2)*2-1,o=[],l=[],p,s=1;s<=t;s++)(s===1||s===t||s>=u&&s<=m||r&&s<g&&e<g-2||r&&s>t-a&&e>t-a+2)&&o.push(s);return o.forEach(function(d){p&&(d-p===2?l.push(p+1):d-p!==1&&l.push("...")),l.push(d),p=d}),l}},methods:{previousPage(){this.selectPage(this.currentPage-1)},nextPage(){this.selectPage(this.currentPage+1)},selectPage(e){e!=="..."&&this.$emit("pagination-change-page",e)}},render(){return this.$slots.default({data:this.data,limit:this.limit,computed:{isApiResource:this.isApiResource,currentPage:this.currentPage,firstPageUrl:this.firstPageUrl,from:this.from,lastPage:this.lastPage,lastPageUrl:this.lastPageUrl,nextPageUrl:this.nextPageUrl,perPage:this.perPage,prevPageUrl:this.prevPageUrl,to:this.to,total:this.total,pageRange:this.pageRange},prevButtonEvents:{click:e=>{e.preventDefault(),this.previousPage()}},nextButtonEvents:{click:e=>{e.preventDefault(),this.nextPage()}},pageButtonEvents:e=>({click:r=>{r.preventDefault(),this.selectPage(e)}})})}},B=(e,r)=>{const t=e.__vccOpts||e;for(const[n,u]of r)t[n]=u;return t},U={compatConfig:{MODE:3},inheritAttrs:!1,emits:["pagination-change-page"],components:{RenderlessPagination:C},props:{data:{type:Object,default:()=>{}},limit:{type:Number,default:0},keepLength:{type:Boolean,default:!1},itemClasses:{type:Array,default:()=>["bg-white","text-gray-500","border-gray-300","hover:bg-gray-50"]},activeClasses:{type:Array,default:()=>["bg-blue-50","border-blue-500","text-blue-600"]}},methods:{onPaginationChangePage(e){this.$emit("pagination-change-page",e)}}},_=["disabled"],E=i("span",{class:"sr-only"},"Previous",-1),L=i("svg",{class:"h-5 w-5","aria-hidden":"true",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor"},[i("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M15.75 19.5L8.25 12l7.5-7.5"})],-1),$=["aria-current"],D=["disabled"],N=i("span",{class:"sr-only"},"Next",-1),j=i("svg",{class:"w-5 h-5","aria-hidden":"true",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor"},[i("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M8.25 4.5l7.5 7.5-7.5 7.5"})],-1);function z(e,r,t,n,u,m){const g=x("RenderlessPagination");return c(),k(g,{data:t.data,limit:t.limit,"keep-length":t.keepLength,onPaginationChangePage:m.onPaginationChangePage},{default:b(a=>[a.computed.total>a.computed.perPage?(c(),P("nav",h({key:0},e.$attrs,{class:"isolate inline-flex -space-x-px rounded-md shadow-sm","aria-label":"Pagination"}),[i("button",h({class:["relative inline-flex items-center rounded-l-md border px-2 py-2 text-sm font-medium focus:z-20 disabled:opacity-50",t.itemClasses],disabled:!a.computed.prevPageUrl},f(a.prevButtonEvents,!0)),[v(e.$slots,"prev-nav",{},()=>[E,L])],16,_),(c(!0),P(y,null,w(a.computed.pageRange,(o,l)=>(c(),P("button",h({class:["relative inline-flex items-center border px-4 py-2 text-sm font-medium focus:z-20",[o==a.computed.currentPage?t.activeClasses:t.itemClasses,o==a.computed.currentPage?"z-30":""]],"aria-current":a.computed.currentPage?"page":null,key:l},f(a.pageButtonEvents(o),!0)),R(o),17,$))),128)),i("button",h({class:["relative inline-flex items-center rounded-r-md border px-2 py-2 text-sm font-medium focus:z-20 disabled:opacity-50",t.itemClasses],disabled:!a.computed.nextPageUrl},f(a.nextButtonEvents,!0)),[v(e.$slots,"next-nav",{},()=>[N,j])],16,D)],16)):A("",!0)]),_:3},8,["data","limit","keep-length","onPaginationChangePage"])}const M=B(U,[["render",z]]);export{M as i};
