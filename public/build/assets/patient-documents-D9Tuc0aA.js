import{D as c}from"./dataTables-QMTTuZ0r.js";import"./dataTables.buttons-BoBoCEal.js";import"./dataTables.responsive-EYTYxJWh.js";import{d as u,e as m}from"./edit-document-modal-BzKYYrgM.js";import{c as f}from"./create-consultation-document-modal-C7Sg7G6q.js";import"./delete-document-modal-Cer9-jdY.js";import"./buttons.print-BYYIKVPZ.js";import"./modal-MwHSR4zk.js";import"./view-appointment-modal-DBPOqfgX.js";import"./edit-appointment-modal-BJO8zgPV.js";const p={consultation:{label:"Consultation",cls:"bg-blue-100 text-blue-800"},"medical-certificate":{label:"Medical Certificate",cls:"bg-emerald-100 text-emerald-800"},"referral-letter":{label:"Referral Letter",cls:"bg-amber-100 text-amber-800"},prescription:{label:"Prescription",cls:"bg-purple-100 text-purple-800"}};function b(a){const o=p[a]??{label:a??"—",cls:"bg-gray-100 text-gray-700"};return`<span class="px-2 py-0.5 rounded text-xs font-semibold ${o.cls}">${o.label}</span>`}function g(a){if(!a)return"—";const o=new Date(a);return Number.isNaN(o.getTime())?a:o.toLocaleDateString("en-US",{month:"long",day:"numeric",year:"numeric"})}document.addEventListener("DOMContentLoaded",function(){const a=window.location.pathname.split("/"),o=a[a.length-1],i=new c("#patientDocumentsTable",{retrieve:!0,ajax:{url:`/consultations/patient/${o}`,dataSrc:"",headers:{Accept:"application/json"}},initComplete:function(){this.api().column(1).search("Consultation").draw()},layout:{topStart:null,topEnd:null,bottomStart:"pageLength",bottom2Start:"info",bottomEnd:"paging"},responsive:!0,searching:!0,lengthChange:!0,filtering:!0,paging:!0,fixedColumns:!0,columnDefs:[{targets:-1,className:"flex justify-center",width:10},{targets:0,width:10,className:"text-center flex justify-center w-10 h-[2.35em]"}],columns:[{data:null,title:"#",className:"rounded-tl-md text-center",orderable:!1,searchable:!1,render:(e,r,t,n)=>n.row+n.settings._iDisplayStart+1},{data:"document_type",title:'<div class="ml-5">Record Type</div>',render:e=>`<div class="ml-5">${b(e)}</div>`},{data:"created_at",title:"Date",render:e=>g(e)},{data:null,title:"Actions",orderable:!1,searchable:!1,className:"rounded-tr-md flex justify-left",render:(e,r,t)=>{const n=t.document_type??"consultation",d=n==="consultation"?`<button type="button"
                class="dt-action px-2 py-1 border-2 border-emerald-700 text-emerald-700 hover:bg-emerald-100 rounded-md size-8 flex justify-center items-center"
                data-action="add-document"
                data-id="${t.id??""}"
                title="Generate Document">
                <i class="fa-solid fa-file-medical fa-sm"></i>
              </button>`:"";return`
            <div class="w-full flex flex-row justify-left items-center gap-2">
              <a type="button"
                class="dt-action px-2 py-1 border-2 border-blue-950 text-blue-950 hover:bg-indigo-100 rounded-md size-8 flex justify-center items-center"
                href="/consultations/${t.id??""}/${n}"
                target="_blank"
                rel="noopener noreferrer"
                title="View PDF">
                <i class="fa-solid fa-eye fa-sm"></i>
              </a>
              <button type="button"
                class="dt-action px-2 py-1 border-2 border-amber-800 text-amber-800 hover:bg-amber-100 rounded-md size-8 flex justify-center items-center"
                data-modal-open="edit-document-p"
                data-action="edit"
                data-id="${t.id??""}"
                title="Edit">
                <i class="fa-solid fa-pen fa-sm"></i>
              </button>
              <button type="button"
                class="dt-action px-2 py-1 border-2 border-red-800 text-red-800 hover:bg-red-100 rounded-md size-8 flex justify-center items-center"
                data-modal-open="delete-document"
                data-action="delete"
                data-id="${t.id??""}"
                title="Delete">
                <i class="fa-solid fa-trash fa-sm"></i>
              </button>
              ${d}
            </div>
          `}}]});document.querySelector("#patientDocumentsTable tbody").addEventListener("click",async e=>{var d,s;const r=e.target.closest(".dt-action");if(!r)return;const t=r.dataset.action,n=r.dataset.id;t==="delete"&&u(n),t==="edit"&&(m(n),(d=window.Modal)==null||d.open("edit-document-p")),t==="add-document"&&(await f(n),(s=window.Modal)==null||s.open("create-consultation-document"))});const l=document.getElementById("searchRecords");l&&(l.addEventListener("input",e=>{i.search(e.target.value).draw()}),l.addEventListener("change",e=>{i.search(e.target.value).draw()})),window.refreshDocumentsTable=()=>i.ajax.reload(null,!1),window.filterDocumentsTable=e=>{i.column(1).search(e?`^${e}$`:"",!0,!1).draw()}});
