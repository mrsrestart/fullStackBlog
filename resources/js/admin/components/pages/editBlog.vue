<template>
    <div>
        <div class="content">
            <div class="container">
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <p class="_title0">Create BLog
                    </p>

                    <div class="_overflow _table_div">
                        <div class="inputCss">
                            <Input type="text" v-model="data.title" style="width: 100%;padding: 10px" placeholder="post title...."></Input>
                        </div>
                        <div class="inputCssBlogInput">
                            <Editor v-if="initData"
                                ref="editor"
                                autofocus
                                holder-id="codex-editor"
                                save-button-id="save-button"
                                :init-data="initData"
                                @save="onSave()"
                                :config="config"
                            />
                        </div>
                        <div class="inputCss">
                            <Input type="textarea" v-model="data.post_excerpt" :rows="7" placeholder="Post Expert"></Input>
                        </div>
                        <div class="inputCss">
                            <Select filtrable multiple v-model="data.category_id" placeholder="Select Category">
                                <Option v-for="(c,i) in this.categoryData" :value="c.id" :key="i">{{
                                        c.categoryName
                                    }}
                                </Option>
                            </Select>
                        </div>

                        <div class="inputCss">
                            <Select filtrable multiple v-model="data.tag_id" placeholder="Select Tag..">
                                <Option v-for="(t,i) in this.tagData" :value="t.id" :key="i">{{
                                        t.tagName
                                    }}
                                </Option>
                            </Select>
                        </div>
                        <div class="inputCss">
                            <Input type="textarea" v-model="data.metaDescription" :rows="7" placeholder="Meta Description"></Input>
                        </div>
                        <button class="bttonStyle" :loding="this.isCreating" :disabled="this.isCreating" @click="save">{{this.isCreating ? 'Pls Wait...' : 'Edit Blog'}}</button>


                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import deleteModal from '../shareComponents/deleteModal';

import ImageTool from '@editorjs/image';

const InlineCode = require('@editorjs/inline-code');
const Quote = require('@editorjs/quote');
import List from '@editorjs/list';

const LinkTool = require('@editorjs/link');
const Checklist = require('@editorjs/checklist');

export default {
    data() {
        return {
            config: {
                image: {
                    field: 'image',
                    types: 'image/*',
                },
                tools: {
                    header: require('@editorjs/header'),
                    class: ImageTool,
                    inlineCode: {
                        class: InlineCode,
                        shortcut: 'ALT+SHIFT+M',

                    },
                    quote: Quote,
                    list: {
                        class: List,
                        inlineToolbar: true,
                    },
                    linkTool: {
                        class: LinkTool,
                        config: {
                            endpoint: 'http://localhost:8008/fetchUrl', // Your backend endpoint for url data fetching
                        }
                    },
                    checklist: {
                        class: Checklist,
                        inlineToolbar: true,
                    },
                }
            },
            initData: null,
            articleHtml: "",
            categoryData: [],
            tagData: [],
            data:{
                title:'',
                post:'',
                post_excerpt:'',
                metaDescription:'',
                category_id: [],
                tag_id: []
            },
            isCreating:false
        }
    },
    methods: {
        save() {

            this.$refs.editor._data.state.editor.save()
                .then(async (data) => {
                    // Do what you want with the data here
                    this.outPutHtml(data.blocks)
                    const id = parseInt(this.$route.params.id)

                    this.data.post = this.articleHtml
                    this.data.jsonData = JSON.stringify(data)
                    this.isCreating = true
                    const res = await this.callApi(this.data,'post','/app/edit_blog/'+id);
                    console.log("this is rest" , res);
                    if(res.status == 200){
                        this.success("Your Blog Successfully Edited")
                        this.isCreating = false
                    }else{
                        this.swr();
                    }
                })
                .catch(err => {
                    console.log(err)
                })
        },
        outPutHtml(jsonData) {
            jsonData.map(obj => {
                switch (obj.type) {
                    case 'paragraph':
                        this.articleHtml += this.makeParagraph(obj)
                        break;
                    case 'class':
                        this.articleHtml += this.makeImage(obj)
                        break;
                    case 'header':
                        this.articleHtml += this.makeHeader(obj)
                        break;
                    case 'code':
                        this.articleHtml += this.makeCode(obj)
                        break;
                    case 'list':
                        this.articleHtml += this.makeList(obj)
                        break;
                    case 'quote':
                        this.articleHtml += this.makeQuote(obj)
                        break;
                    case 'warning':
                        this.articleHtml += this.makeWarning(obj)
                        break;
                    case 'link':
                        this.articleHtml += this.makeLink(obj)
                        break;
                    default:
                        return '';
                }

            })
            console.log("this is a part of html", this.articleHtml)
        }

    },
    async created() {
        const id = parseInt(this.$route.params.id)

        if(!id){
            this.$router.push('/notfound')
        }
        const [cat , tag, blog] = await Promise.all([
            this.callApi('', 'get', '/app/get_cat'),
            this.callApi('', 'get', '/app/get_tag'),
            this.callApi('', 'get', '/app/get_blog_data/'+id)
        ])
        if (cat.status == 200 && tag.status == 200) {
            if(!blog.data){
                return this.$router.push('/notfound')
            }
            this.initData = JSON.parse(blog.data.jsonData)

            this.category = cat.data
            this.tag = tag.data
            for (let t of blog.data.tag){
                this.data.tag_id.push(t.id)
            }
            for (let c of blog.data.cat){
                this.data.category_id.push(c.id)
            }
            this.data.title = blog.data.title
            this.data.post_excerpt = blog.data.post_excerpt
            this.data.metaDescription = blog.data.metaDescription
            this.data.metaDescription = blog.data.metaDescription
            this.categoryData = cat.data
            this.tagData = tag.data

        } else {
            this.swr()
        }
    }


}
</script>
<style>
.inputCssBlogInput {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #dcdee2;
    border-radius: 10px;
}
.inputCss{
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
}
.bttonStyle{
    color: white;
    background: red;
    text-align: center;
    padding: 10px;
    border-radius: 10px;
}
</style>
