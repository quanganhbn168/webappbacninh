/* resources/js/tools/bulk-anh-cover.js */
window.BulkThumb = function(){
    return {
        urlsText: '',
        items: [],
        isLoading: false,
        error: '',
        sortBy: 'none',
        downloadZip: false,

        init(){},

        clearAll(){
            this.items = [];
            this.urlsText = '';
            this.error = '';
        },

        selectedCount(){
            return this.items.filter(i=>i.selected).length;
        },

        selectAll(){
            this.items.forEach(i=>i.selected = true);
        },

        toggleSelectAll(e){
            const checked = e.target.checked;
            this.items.forEach(i=>i.selected = checked);
        },

        moveUp(idx){
            if(idx<=0) return;
            [this.items[idx-1], this.items[idx]] = [this.items[idx], this.items[idx-1]];
        },

        moveDown(idx){
            if(idx>=this.items.length-1) return;
            [this.items[idx+1], this.items[idx]] = [this.items[idx], this.items[idx+1]];
        },

        applySort(){
            if(this.sortBy==='none') return;
            if(this.sortBy==='provider'){
                this.items.sort((a,b)=>a.provider.localeCompare(b.provider));
            }else if(this.sortBy==='title_asc'){
                this.items.sort((a,b)=>a.title.localeCompare(b.title));
            }else if(this.sortBy==='title_desc'){
                this.items.sort((a,b)=>b.title.localeCompare(a.title));
            }
        },

        parseUrls(){
            this.error = '';
            let urls = this.urlsText.split('\n')
                .map(s=>s.trim()).filter(Boolean);

            if(urls.length===0){
                this.error = 'Vui lòng dán ít nhất 1 đường dẫn.';
                return;
            }
            this.fetchAll(urls);
        },

        async fetchAll(urls){
            this.isLoading = true;
            try{
                for(const u of urls){
                    // Bỏ trùng theo original_url
                    if (this.items.find(i=>i.original_url===u)) continue;

                    const res = await fetch(this.route('cover.getInfo'), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                        },
                        body: JSON.stringify({ url: u })
                    });

                    const data = await res.json();
                    if(!res.ok || !data.success){
                        console.warn('Bỏ qua link lỗi:', u, data?.message);
                        continue;
                    }

                    // Chuẩn hóa title TikTok: username-videoId
                    let title = data.title || '';
                    let playerUrl = data.url;

                    if (data.provider === 'tiktok') {
                        const m = u.match(/tiktok\.com\/@([^\/]+)\/video\/(\d+)/i);
                        if(m){
                            const user = m[1];
                            const id = m[2];
                            title = `${user}-${id}`;
                            playerUrl = `https://www.tiktok.com/player/v1/${id}`; // theo chuẩn của anh
                        }
                    }

                    this.items.push({
                        uid: crypto.randomUUID(),
                        provider: data.provider,
                        thumbnail_url: data.thumbnail_url,
                        original_url: u,
                        url: playerUrl,
                        title: title,
                        selected: true
                    });
                }
                this.applySort();
            }catch(e){
                console.error(e);
                this.error = 'Đã có lỗi khi lấy dữ liệu. Vui lòng thử lại.';
            }finally{
                this.isLoading = false;
            }
        },

        downloadSelected(){
            const selected = this.items.filter(i=>i.selected);
            if(selected.length===0){
                this.error = 'Vui lòng chọn ít nhất một mục để lưu.';
                return;
            }
            const box = document.getElementById('bulkPayload');
            box.innerHTML = '';
            selected.forEach((it, idx)=>{
                box.insertAdjacentHTML('beforeend', `
                    <input type="hidden" name="items[${idx}][image_url]" value="${it.thumbnail_url}">
                    <input type="hidden" name="items[${idx}][filename]"  value="${it.title}">
                    <input type="hidden" name="items[${idx}][provider]"  value="${it.provider}">
                    <input type="hidden" name="items[${idx}][original_url]" value="${it.original_url}">
                    <input type="hidden" name="items[${idx}][url]" value="${it.url}">
                `);
            });
            document.getElementById('bulkForm').submit();
        },

        // helper: resolve named route from Blade (không cần nếu anh dùng route cố định)
        route(name){
            // inject từ blade qua data-attrs hoặc hardcode
            const map = {
                'cover.getInfo': document.querySelector('meta[name=cover-get-info]')?.getAttribute('content') || '/get-info'
            };
            return map[name] || '/get-info';
        }
    }
};
