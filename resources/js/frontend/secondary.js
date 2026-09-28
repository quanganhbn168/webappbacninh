/* WebApp Bắc Ninh — catalog filters, pricing tabs, consultation forms and contact
 * shortcuts shared by the public pages. Plain JavaScript on top of Bootstrap.
 */
(() => {
  'use strict';
  const config = () => window.WEBAPP_SITE_CONFIG || {};
  const fold = value => String(value || '').normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/đ/g,'d').replace(/Đ/g,'D').toLowerCase().trim();
  let toastTimer;
  function toast(message) {
    const el=document.getElementById('toast'); if(!el) return;
    el.textContent=message; el.hidden=false; clearTimeout(toastTimer); toastTimer=setTimeout(()=>{el.hidden=true;},6500);
  }
  function match(el,category,query) {return (category==='all'||(el.dataset.cat||'').split(' ').includes(category)) && fold(el.dataset.title).includes(fold(query));}
  function updateEmpty(root) {const items=[...root.querySelectorAll('[data-catalog-item]')];const notice=root.querySelector('[data-empty]');if(notice)notice.hidden=items.some(i=>getComputedStyle(i).display!=='none');}
  function setStatus(form,message,state='') {const el=form.querySelector('.form-status');if(el){el.textContent=message;el.className='form-status '+state;}}
  async function submitConsultation(event) {
    event.preventDefault();const form=event.currentTarget||event.target;
    if(form.dataset.busy==='1'||!form.reportValidity())return;
    const fd=new FormData(form);const payload={name:String(fd.get('name')||'').trim(),phone:String(fd.get('phone')||'').trim(),email:String(fd.get('email')||'').trim(),company:String(fd.get('company')||'').trim(),service:String(fd.get('service')||''),message:String(fd.get('message')||'').trim(),consent:fd.get('consent')==='on',page:location.pathname};
    const adapter=config().submitConsultation;
    if(typeof adapter!=='function'){setStatus(form,'Không thể gửi yêu cầu lúc này. Vui lòng liên hệ qua số điện thoại ở cuối trang.');return;}
    const submit=form.querySelector('[type=submit]');form.dataset.busy='1';if(submit)submit.disabled=true;setStatus(form,'Đang gửi yêu cầu...');
    try {const result=await adapter(payload);if(!result||result.ok!==true)throw new Error(result?.message||'Máy chủ chưa xác nhận tiếp nhận. Vui lòng thử lại.');setStatus(form,result.message||'Yêu cầu đã được gửi thành công.','success');form.reset();}
    catch(error){setStatus(form,error instanceof Error?error.message:'Không thể gửi yêu cầu. Vui lòng thử lại.','error');}
    finally{form.dataset.busy='0';if(submit)submit.disabled=false;}
  }
  function initInteractions() {
    document.querySelectorAll('[data-catalog]').forEach(root=>{
      let category='all',query='',sort='featured';const items=[...root.querySelectorAll('[data-catalog-item]')];
      function render(){const ordered=[...items];if(sort!=='featured')ordered.sort((a,b)=>(sort==='za'?-1:1)*a.dataset.title.localeCompare(b.dataset.title,'vi'));items.forEach(el=>{el.style.display=match(el,category,query)?'':'none';el.style.order=ordered.indexOf(el);});root.querySelectorAll('[data-filter]').forEach(b=>{b.classList.toggle('active',b.dataset.filter===category);b.setAttribute('aria-pressed',String(b.dataset.filter===category));});updateEmpty(root);}
      root.addEventListener('click',e=>{const button=e.target.closest('[data-filter]');if(button){category=button.dataset.filter;render();}});
      root.querySelectorAll('[data-search]').forEach(input=>input.addEventListener('input',()=>{query=input.value;render();}));
      root.querySelectorAll('[data-sort]').forEach(select=>select.addEventListener('change',()=>{sort=select.value;render();}));
      render();
    });
    document.querySelectorAll('[data-pricing]').forEach(root=>{
      const render=category=>{root.querySelectorAll('[data-filter]').forEach(b=>{b.classList.toggle('active',b.dataset.filter===category);b.setAttribute('aria-pressed',String(b.dataset.filter===category));});root.querySelectorAll('[data-price-panel]').forEach(p=>{p.hidden=p.dataset.pricePanel!==category;});};
      root.addEventListener('click',e=>{const b=e.target.closest('[data-filter]');if(b)render(b.dataset.filter);});render('all');
    });
    const toggle=document.querySelector('[data-mobile-toggle]'),nav=document.querySelector('#mobile-nav');
    if(toggle&&nav){nav.hidden=true;toggle.setAttribute('aria-expanded','false');toggle.addEventListener('click',()=>{const open=toggle.getAttribute('aria-expanded')!=='true';toggle.setAttribute('aria-expanded',String(open));nav.hidden=!open;});}
    document.querySelectorAll('[data-consultation]').forEach(form=>form.addEventListener('submit',e=>{submitConsultation(e);}));
  }
  document.addEventListener('frontend:ready',()=>{
    initInteractions();
    document.querySelectorAll('[data-scroll-top]').forEach(button=>button.addEventListener('click',()=>window.scrollTo({top:0,behavior:'smooth'})));
    const dialog=document.getElementById('detail-dialog');
    document.addEventListener('click',e=>{
      const detail=e.target.closest('[data-detail],[data-demo]');
      if(detail){const card=detail.closest('.card');if(!card)return;const title=(card.querySelector('h3,h2')?.textContent||'Thông tin dự án').trim();const url=(detail.hasAttribute('data-demo')?config().demoUrls:config().detailUrls)?.[title];if(url){location.assign(url);return;}if(!dialog)return;document.getElementById('detail-title').textContent=title;document.getElementById('detail-description').textContent=card.querySelector('.card-body>p,p')?.textContent||'Bản xem trước giao diện sản phẩm.';const holder=document.getElementById('detail-image');holder.replaceChildren();const original=card.querySelector('img');if(original){const image=document.createElement('img');image.src=original.src;image.alt=original.alt;holder.appendChild(image);}window.bootstrap.Modal.getOrCreateInstance(dialog).show();}
      const contact=e.target.closest('[data-contact]');if(contact){const kind=contact.dataset.contact;const value=config().contact?.[kind];if(!value){toast('Kênh liên hệ này đang được cập nhật. Vui lòng gọi số điện thoại ở cuối trang.');return;}if(kind==='phone')location.href='tel:'+String(value).replace(/[^+0-9]/g,'');else if(kind==='email')location.href='mailto:'+encodeURIComponent(value);else window.open(value,'_blank','noopener,noreferrer');}
      const social=e.target.closest('[data-social]');if(social){const url=config().socials?.[social.dataset.social];url?window.open(url,'_blank','noopener,noreferrer'):toast('Kênh mạng xã hội đang được cập nhật.');}
      const policy=e.target.closest('[data-policy]');if(policy){const url=config()[policy.dataset.policy==='privacy'?'privacyUrl':'termsUrl'];url?location.assign(url):toast('Nội dung chính sách đang được cập nhật.');}
    });
    document.querySelectorAll('[data-newsletter]').forEach(form=>form.addEventListener('submit',async e=>{
      e.preventDefault();if(!form.reportValidity())return;const adapter=config().subscribeNewsletter;
      if(typeof adapter!=='function'){setStatus(form,'Đăng ký nhận tin đang tạm dừng. Bạn có thể liên hệ để nhận tư vấn trực tiếp.');return;}
      const button=form.querySelector('[type=submit]');button.disabled=true;
      try{const result=await adapter({email:form.querySelector('input').value.trim()});if(!result?.ok)throw new Error(result?.message||'Không thể đăng ký.');setStatus(form,result.message||'Đã đăng ký nhận tin.','success');form.reset();}catch(error){setStatus(form,error.message||'Không thể đăng ký.','error');}finally{button.disabled=false;}
    }));
    const service=new URLSearchParams(location.search).get('service'),select=document.querySelector('select[name=service]');if(service&&select){const found=[...select.options].find(o=>o.value===service);if(found)select.value=service;}
  });
  window.WEBAPP_FRONTEND={fold,submitConsultation,toast};
})();
