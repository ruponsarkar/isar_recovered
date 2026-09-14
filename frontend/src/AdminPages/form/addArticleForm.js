import React from 'react'
import FormJoditEditor from '../component/FormJoditEditor'

const AddArticleForm = (props) => {
    return (
        <>
            <div className='row'>
                <div className="form-group">
                    <div className="col-12 mt-2">
                        <label htmlFor="">Title</label>
                        <input type="text" name='name' onChange={props.handleAddInput} value={props.articleData.name} placeholder='Enter Journal Title' className='form-control' />
                    </div>
                    <div className="col-12 mt-2">
                        <label htmlFor="">Author Name</label>
                        <input type="text" name='aname' onChange={props.handleAddInput} value={props.articleData.aname} className='form-control' placeholder='Enter Author Name' />
                    </div>
                    <div className="col-12 mt-2">
                        <label htmlFor="">Author Designation</label>
                        <input type="text" name='designation' onChange={props.handleAddInput} value={props.articleData.designation} className='form-control' />
                    </div>

                    <div className="col-12 mt-2">
                        <label htmlFor="">Google Scholar</label>
                        <input type="text" name='googleScholar' onChange={props.handleAddInput} value={props.articleData.googleScholar} className='form-control' />
                    </div>

                    <div className='row'>
                        <div className="col-6 mt-2">
                            <label htmlFor="">DOI</label>
                            <input type="text" name='doi' onChange={props.handleAddInput} value={props.articleData.doi} className='form-control' />
                        </div>
                        <div className="col-3 mt-2">
                            <label htmlFor="">Page</label>
                            <input type="text" name='page' onChange={props.handleAddInput} value={props.articleData.page} className='form-control' />
                        </div>
                        <div className="col-3 mt-2">
                            <label htmlFor="">Published Date</label>
                            <input type="date" name='published_date' onChange={props.handleAddInput} value={props.articleData.published_date} className='form-control' />
                        </div>
                    </div>
                    <div className="col-12 mt-2">
                        <label htmlFor="">Keywords</label>
                        <textarea name='keywords' className='form-control' onChange={props.handleAddInput}>
                            {props.articleData.keywords}
                        </textarea>
                    </div>
                    <div className="col-12 mt-2">
                        <label htmlFor="">Abstract</label>
                        {/* <textarea name='abstract' className='form-control' onChange={props.handleAddInput}>
                            {props.articleData.abstract}
                        </textarea> */}

                        <FormJoditEditor
                            name="abstract"
                            value={props.articleData.abstract}
                            onChange={props.handleAddInput}
                        />
                    </div>
                    <div className="col-12 mt-2">
                        <label htmlFor="">File</label>
                        <input type="file" onChange={props.handleFile} className='form-control' />
                    </div>


                </div>
            </div>
        </>
    )
}

export default AddArticleForm