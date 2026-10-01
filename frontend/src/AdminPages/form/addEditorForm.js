import React from 'react'


const AddEditorForm = (props) => {





    return (
        <>
            <div className='row'>
                <div className="form-group">
                    <div className='row'>
                        <div className="col-6">
                            <label htmlFor="">Editor Type</label>
                            <select name="type" onChange={props.handleAddInput} value={props.editorData.type} className='form-control'>
                                <option>--Select--</option>
                                <option value='chief'>Chief</option>
                                <option value='deputy_chief'>Deputy Chief Editor</option>
                                <option value='ass'>Associative</option>
                            </select>
                        </div>
                        <div className="col-6">
                            <label htmlFor="">Editor Name</label>
                            <input type="text" name='name' onChange={props.handleAddInput} value={props.editorData.name} className='form-control' />
                        </div>
                    </div>


                    <div className="col-12">
                        <label htmlFor="">Details</label>
                        <input type="text" name='details' onChange={props.handleAddInput} value={props.editorData.details} className='form-control' />
                    </div>

                    <div className="col-12">
                        <label htmlFor="">Biography</label>

                        <textarea className='form-control' name='biography' onChange={props.handleAddInput}>{props.editorData.biography}</textarea>
                    </div>
                    <div className="col-12">
                        <label htmlFor="">Research Interest</label>
                        <input type="text" name='interest' onChange={props.handleAddInput} value={props.editorData.interest} className='form-control' />
                    </div>


                    <div className='row'>
                        <div className="col-8">
                            <label htmlFor="">Profile Link</label>
                            <input type="text" name='profile' onChange={props.handleAddInput} value={props.editorData.profile} className='form-control' />
                        </div>
                        <div className="col-4">
                            <label htmlFor="">Image</label>
                            <input type="file" onChange={props.handleEditorPhoto} className='form-control' />
                        </div>
                    </div>

                    <div className="col-12 mt-4">
                        <div className="form-check">
                            <label className="form-check-label">
                            <input className="form-check-input" type="checkbox" name="feature" value="" checked={props.checked} onChange={props.handleCheck} id="flexCheckIndeterminate" />
                                Add as Top Editor On Side Bar
                            </label>
                        </div>
                    </div>


                </div>

                {/* <div className="col-12 m-2 text-center">
                    <button className='btn btn-success'>Save</button>
                </div> */}
            </div>
        </>
    )
}


export default AddEditorForm;