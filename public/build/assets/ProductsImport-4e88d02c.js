import{r,q as V,a as o,c as a,e,p as D,b as w,w as M,g as j,u as p,n as h,t as _,F as g,f as x,i as y}from"./app-af608028.js";import{_ as B}from"./Modal-49f06bdc.js";import{_ as E}from"./InputError-e63fa01d.js";const O=e("svg",{class:"text-white h-6 w-6 transform rotate-180",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24",fill:"none",stroke:"currentColor","stroke-width":"2","stroke-linecap":"round","stroke-linejoin":"round"},[e("path",{d:"M12 5v14M5 12l7 7 7-7"})],-1),U={class:"p-6"},A=e("h2",{class:"text-lg font-semibold"}," Import Products from a CSV File ",-1),$=e("p",{class:"mt-1 text-sm text-gray-600"}," The CSV file must contain the following columns: SKU, Name, Description, Price, and Quantity. ",-1),q=e("p",{class:"mt-1 text-sm text-gray-600"}," The same format as the one used in the export file. ",-1),H=e("p",{class:"mt-1 text-sm text-gray-600"}," Please note that if an error is found in any row, it will be reported afterwards, but valid rows will still be processed. ",-1),K=["onSubmit"],L={class:"mt-4"},Q=e("label",{class:"block mb-2 text-sm",for:"file_input"},"Upload file",-1),Y={class:"flex justify-between mt-4"},z=["disabled"],G={key:0,class:"flex items-center"},J=e("div",{class:"dots-loading flex items-center"},[e("div"),e("div"),e("div")],-1),W=e("p",{class:"ml-2 inline-block align-middle"},"Importing",-1),X=[J,W],Z={key:0,class:"mt-4"},ee={class:"text-lg font-semibold"},te={key:1,class:"mt-4 overflow-auto max-h-72 bg-gray-100 p-2"},se=e("p",{class:"mt-1 text-md text-black"}," The following rows have errors. Please note that valid rows have already been processed: ",-1),ne={__name:"ProductsImport",setup(oe){const m=r(!1),l=r(""),n=r({}),i=r("");let d=null,c=r(!1),v=null,f=r(!1);const k=()=>{m.value=!0},P=()=>{m.value=!1},C=t=>{d=t.target.files[0]},I=async()=>{if(l.value="",n.value={},i.value="",c.value){l.value="Import already in progress";return}if(!d){l.value="Please select a file";return}const t=new FormData;t.append("file",d);try{await y.post(route("api.admin.products.import"),t,{headers:{"Content-Type":"multipart/form-data"}}),S(d.name)}catch(s){s.response.status===422?l.value=s.response.data.errors.file[0]:l.value="Something went wrong. Please try again later."}},S=t=>{c.value=!0,f.value=!0,v=setInterval(()=>F(t),3e3)},F=async t=>{try{const s=await y.get(route("api.admin.products.import.check",{fileName:t}));s.data.status==="HAS_ERRORS"&&(i.value="The import has been done, but there are some errors. Please check the error rows and fix them, then reload the file and import again.",n.value=s.data.errors,u()),s.status===200&&s.data.status==="READY"&&(i.value="The import has been done successfully.",u())}catch{u()}},u=()=>{clearInterval(v),c.value=!1,f.value=!1};return V(u),(t,s)=>(o(),a(g,null,[e("button",{class:"flex w-full sm:w-auto items-center px-4 py-2 bg-green-500 hover:bg-green-600 font-bold focus:outline-none text-white rounded",onClick:k},[O,D(" Import Products ")]),w(B,{show:m.value,onClose:P},{default:M(()=>[e("div",U,[A,$,q,H,e("form",{onSubmit:j(I,["prevent"])},[e("div",L,[Q,e("input",{class:"file:p-2 file:border-none block w-full text-gray-900 border border-gray-300 rounded-lg cursor-pointer focus:outline-none",id:"file_input",type:"file",accept:".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel",onChange:C},null,32),w(E,{class:"mt-2",message:l.value},null,8,["message"])]),e("div",Y,[e("button",{type:"submit",disabled:p(c)||p(f),class:"inline-flex justify-center py-1 px-4 border border-transparent shadow-sm font-medium rounded-md text-white bg-green-500 hover:bg-green-600 disabled:bg-green-600 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"}," Import ",8,z),p(c)?(o(),a("div",G,X)):h("",!0)])],40,K),i.value?(o(),a("div",Z,[e("h3",ee,_(i.value),1)])):h("",!0),n.value&&Object.keys(n.value).length>0?(o(),a("div",te,[se,e("ul",null,[(o(!0),a(g,null,x(n.value,(R,b)=>(o(),a("li",{key:b,class:"mt-2 text-red-500"},[e("strong",null,"Row "+_(b)+":",1),e("ul",null,[(o(!0),a(g,null,x(R,(T,N)=>(o(),a("li",{key:N,class:"ml-4"}," - "+_(T),1))),128))])]))),128))])])):h("",!0)])]),_:1},8,["show"])],64))}};export{ne as default};
