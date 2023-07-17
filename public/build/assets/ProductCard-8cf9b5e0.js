import{u as y}from"./cart-fd50cb26.js";import{d as v,a as w,c as k,e as t,b as m,w as h,u as a,m as f,p as x,t as n}from"./app-af608028.js";import{g as p}from"./getProductImage-92473f05.js";const C={class:"flex items-center p-4 border rounded shadow-md"},b={class:"mr-4"},q=["src","alt"],$={class:"flex-1"},B={class:"flex items-center mt-1 text-sm"},P=t("span",{class:"text-gray-600 mr-1"},"Stock:",-1),j={class:"text-gray-900 font-semibold"},N=t("svg",{class:"h-5 w-5 mr-1",fill:"none","stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",viewBox:"0 0 24 24",stroke:"currentColor"},[t("path",{d:"M6 18L18 6M6 6l12 12"})],-1),S={class:"flex items-center mt-4"},_={class:"flex items-center mr-4"},M=t("svg",{class:"h-5 w-5",fill:"none","stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",viewBox:"0 0 24 24",stroke:"currentColor"},[t("path",{d:"M20 12H4"})],-1),F=[M],T={class:"text-gray-700 mx-2"},V=t("svg",{class:"h-5 w-5",fill:"none","stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",viewBox:"0 0 24 24",stroke:"currentColor"},[t("path",{d:"M12 6v6m0 0v6m0-6h6m-6 0H6"})],-1),E=[V],H={class:"text-gray-600"},L={__name:"ProductCard",props:{product:{type:Object,required:!0},quantity:{type:Number,required:!0},fetchProducts:{type:Function,required:!0}},setup(e){const{product:g,quantity:Q,fetchProducts:i}=e,d=y(),s=v.useToast(),u=async(r,o)=>{if(s.clear(),o===0){await l(r);return}if(o>g.stock){s.error("Not enough stock!");return}s.default("Updating quantity...");try{await d.addToCart(r,o),await i(),s.success("Quantity updated!")}catch{s.error("Something went wrong!")}},l=async r=>{s.clear(),s.default("Removing product...");try{await d.removeFromCart(r),await i(),s.success("Product removed!")}catch{s.error("Something went wrong!")}};return(r,o)=>(w(),k("div",C,[t("div",b,[m(a(f),{href:`/products/${e.product.id}`},{default:h(()=>[t("img",{src:a(p)(e.product.image),alt:e.product.name,class:"w-32 h-32 object-cover rounded"},null,8,q)]),_:1},8,["href"])]),t("div",$,[m(a(f),{href:`/products/${e.product.id}`,class:"text-lg font-medium text-gray-900"},{default:h(()=>[x(n(e.product.name),1)]),_:1},8,["href"]),t("div",B,[P,t("span",j,n(e.product.stock),1)]),t("button",{onClick:o[0]||(o[0]=c=>l(e.product.id)),class:"text-red-500 flex items-center mt-2"},[N,x(" Remove ")]),t("div",S,[t("div",_,[t("button",{onClick:o[1]||(o[1]=c=>u(e.product.id,e.quantity-1)),class:"text-gray-500 focus:outline-none focus:text-gray-600"},F),t("span",T,n(e.quantity),1),t("button",{onClick:o[2]||(o[2]=c=>u(e.product.id,e.quantity+1)),class:"text-gray-500 focus:outline-none focus:text-gray-600"},E)]),t("span",H,"$ "+n((e.product.price*e.quantity).toFixed(2)),1)])])]))}};export{L as default};
