import React from 'react'


const AddVolumeForm=(props)=>{
    return (
        <>
        <div className='row'>
                <div className="form-group">
                    <div className="col-12">
                        <label htmlFor=""> Name</label>
                        <input type="text" name='name' onChange={props.handleAddInput} value={props.volumeData.name}  className='form-control' placeholder='' />
                    </div>
                    {/* <div className="col-12">
                        <label htmlFor="">Year</label>
                        <input type="date" name='year' onChange={props.handleAddInput} value={props.volumeData.year} className='form-control' />
                    </div> */}
                </div>
            </div>
        </>
    )
}

export default AddVolumeForm