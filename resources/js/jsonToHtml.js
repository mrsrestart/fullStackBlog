export default {
    data(){
        return{

        }
    },
    methods:{
        makeParagraph(obj){
            return '<p class="blog_post_text">' + obj.data.text + '</p>'

        },
        makeHeader(obj){
            return '<h'+obj.data.level +'>'+ obj.data.text+'</h'+obj.data.level+'>'
        },
        makeCode(obj){
            console.log(obj)
        },
        makeImage(obj){
            console.log(obj)
            return '<div class="image"><img src="'+ obj.data.file.url + '">' +
                '</div>' +
                '<div class="captionOfImage">'+ obj.data.caption +'</div>'
        },
        makeList(obj){
            const listItem =  obj.data.items.map(item=>{
                return '<li>'+ item +'</li>'
            })
            listItem.replace(",", "");

            return '<ol>'
                +
                    listItem
                +
                '</ol>'
        },
        makeQuote(obj){
            return '<blockquote>' + obj.data.text +
                '</blockquote>'
            console.log(obj)
        },
        makeWarning(obj){
            console.log(obj)
        }

    }
}
