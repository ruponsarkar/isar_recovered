import React, { useState, useRef, useCallback, useMemo } from "react";
import JoditEditor from 'jodit-react';
import { useDropzone } from 'react-dropzone';
import axios from 'axios';

import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';


const BlogPanel = () => {

    const editor = useRef(null);

    const [files, setFile] = useState([]);

    const [formData, setFormData] = useState({
        title: '',
        hashtag: '',
        category: '',
        author: '',
        keyword: '',
        metaDescription: '',
        cover: ''
    });

    const [details, setDetails] = useState('');

    const handleInput = (e) => {
        setFormData({
            ...formData,
            [e.target.name]: e.target.value
        })
    }

    const handleFile = (file) => {
        setFormData({
            ...formData,
            cover: file[0]
        })
    }

    const handleJodit = (e) => {
        setDetails(e);
    }

    const handlesubmit = (e) => {

        const api = process.env.REACT_APP_MAIN_API;

        e.preventDefault();

        const url = api + "/postBlog";

        const data = new FormData();

        // files.forEach((file, i) => {
        //     data.append(`cover-${i}`, file);
        //   });

        data.append('cover', files[0])

        data.append('title', formData.title);
        data.append('details', details);
        data.append('hashtag', formData.hashtag);
        data.append('category', formData.category);
        data.append('author', formData.author);
        data.append('keyword', formData.keyword);
        data.append('metaDescription', formData.metaDescription);




        axios.post(url, data).then(res => {
            console.log("==>",res.status);
            if (res.status == 200) {
                toast.success('Successfully Added', {
                    position: "top-right",
                    autoClose: 5000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: true,
                    progress: undefined,
                    theme: "colored",
                });

                setFormData({
                    title: '',
                    hashtag: '',
                    category: '',
                    author: '',
                    keyword: '',
                    metaDescription: '',
                    cover: ''
                });
                setDetails('');
                setFile([]);


            }
        })
            .catch(err => {
                console.log("err",err.response);
                toast.error('Something went wrong.. !', {
                    position: "top-right",
                    autoClose: 5000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: true,
                    progress: undefined,
                    theme: "colored",
                });

            })


    }




    const { getRootProps, getInputProps } = useDropzone({
        accept: "image/*",
        onDrop: (acceptedFiles) => {
            setFile(
                acceptedFiles.map(file => Object.assign(file, {
                    preview: URL.createObjectURL(file)
                }))
            )
        }
    })

    const images = files.map(file => (
        <img src={file.preview} alt="" key={file.name} style={{ 'width': '30%' }} className='mx-2 my-2' />
    ))

    const hasImages = images.length > 0;

    const config = {
        uploader: {
            insertImageAsBase64URI: true
        },
        height: 420,
        triggerChangeEvent: true,
        readonly: false,
    }


    const Jodit = () => {
        return useMemo(() => (
            <JoditEditor
                ref={editor}
                config={config}
                value={details}
                onChange={handleJodit}
            />
        ), [])
    }



    return (
        <>
            <ToastContainer />
            <section id="blogPanel" className="blogPanel my-0 py-0">
                <div className="container">
                    <div className="row">
                        <div className="my-3">
                            <label>Title</label>
                            <input type="text" className="form-control" onChange={handleInput} value={formData.title} name="title" id="title" placeholder="Enter your title here..." required />
                        </div>

                        <div className="my-3">
                            <label>Details</label>

                            {Jodit()}

                            {/* <JoditEditor 
                                ref={editor}
                                config={config}
                                value={content}
                                onChange={newContent=>setContent(newContent)}
                            /> */}
                        </div>

                        <div className="my-3">
                            <label>Hash Tag</label>
                            <input type="text" className="form-control" onChange={handleInput} value={formData.hashtag} name="hashtag" id="hashTag" placeholder="Tag Here..." />
                        </div>

                        <div className="my-3">
                            <div className="row">
                                <div className="col form-group">
                                    <label>Article Category</label><br />
                                    <select onChange={handleInput} name="category" value={formData.category}>
                                        <option value="">Please select category</option>
                                        <option value="a">A</option>
                                        <option value="b">B</option>
                                        <option value="c">C</option>
                                    </select>
                                </div>
                                <div className="col form-group">
                                    <label>Author</label><br />
                                    <select name="author" onChange={handleInput} value={formData.author}>
                                        <option value="">Please select author</option>
                                        <option value="a">A</option>
                                        <option value="b">B</option>
                                        <option value="c">C</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div className="my-3">
                            <label>Meta keywords</label>
                            <input type="text" name="keyword" onChange={handleInput} value={formData.keyword} id="keyword" placeholder="Meta Keywords..." className="form-control" />
                        </div>

                        <div className="my-3">
                            <label>Meta Description</label>
                            <input type="text" name="metaDescription" onChange={handleInput} value={formData.metaDescription} id="desp" placeholder="Meta Descriptions..." className="form-control" />
                        </div>

                        <div className="my-3" {...getRootProps()}>
                            <label>Article cover image</label>&nbsp;
                            <span className="text-danger">Dimension: 300px X 300px</span><br />

                            {!hasImages && (
                                <div className="dropArea py-4">
                                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAASdJREFUSEvtld0xBFEQRs9GQAgrAkRABogAEZDBEoIIEAEiIIMlAzIgAluHu2r26vtTNbVv229zp6dP99ffzExYc0zWXJ8NoKlwj0TbwAVwCOwBXr8Cj8AN8FmjtAAWfE5FozoWP0+wkFMD2PFDpfiwoJC7iFADKMNuU+TfBCfZieSKAHY+S5rn9e/TwWkAvgau8vMccAbcFrp+S0v2djSdZ/s1gJ270Ci+gOlAAp30Dmxlye5BZwn7ieEE2u6oALCzv4dSjg6bB/nu4wR4yQHeyDsyp+gQoCTpE3CcA747HLOcuDt3KJEjHTQgvYCPtLOVHTiSL1YtegGhRBbWx74DY0LHaQBdFv4PtKugllx5E8qi0y6XxUuAMd3/e7b1NR0N2wCaEi4AahcxGSsqP7MAAAAASUVORK5CYII=" />
                                    <input onChange={e => handleFile(e.target.files)} {...getInputProps()} />
                                    <p className="text text-center my-2">Drag and drop files here</p>
                                </div>
                            )}

                        </div>

                        {hasImages && (
                            <div className="text-center">
                                <div className="preview  my-3" style={{ 'display': 'flex', 'flexWrap': 'wrap', 'width': '100%' }}>
                                    {images}
                                </div>

                                <span className="btn btn-primary" {...getRootProps()} style={{ 'width': '200px' }}>
                                    <input {...getInputProps()} />
                                    Replace Image
                                </span>
                            </div>
                        )}

                        <div className="text-center my-3"><button className="btn btn-primary" onClick={handlesubmit} type="submit">Create Post</button></div>


                    </div>
                </div>
            </section>

        </>
    )
}

export default BlogPanel;