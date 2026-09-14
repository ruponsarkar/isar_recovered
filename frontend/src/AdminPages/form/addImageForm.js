import React, { useEffect, useState } from "react";
import { useDropzone } from 'react-dropzone';

const AddImageForm = (props) => {


    const images = props.datafile.map(file => (
        <img src={file.preview} alt="" key={file.name} style={{ 'width': '30%' }} className='mx-2 my-2' />
    ))

    const hasImages = images.length > 0;


    return (
        <>
            <section id="eventForm" className="eventForm">
                <div className="container">
                    <div className="row">
                        <div className="col-md-12">
                            <div className="php-email-form">
                                <div className="my-3" {...props.getRoot}>
                                    <label>Upload More Images</label>&nbsp;
                                    <span className="text-danger">Dimension:   300px X 300px</span><br />

                                    {!hasImages && (
                                        <div className="dropArea py-4">
                                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAASdJREFUSEvtld0xBFEQRs9GQAgrAkRABogAEZDBEoIIEAEiIIMlAzIgAluHu2r26vtTNbVv229zp6dP99ffzExYc0zWXJ8NoKlwj0TbwAVwCOwBXr8Cj8AN8FmjtAAWfE5FozoWP0+wkFMD2PFDpfiwoJC7iFADKMNuU+TfBCfZieSKAHY+S5rn9e/TwWkAvgau8vMccAbcFrp+S0v2djSdZ/s1gJ270Ci+gOlAAp30Dmxlye5BZwn7ieEE2u6oALCzv4dSjg6bB/nu4wR4yQHeyDsyp+gQoCTpE3CcA747HLOcuDt3KJEjHTQgvYCPtLOVHTiSL1YtegGhRBbWx74DY0LHaQBdFv4PtKugllx5E8qi0y6XxUuAMd3/e7b1NR0N2wCaEi4AahcxGSsqP7MAAAAASUVORK5CYII=" />
                                            <input type="file" multiple {...props.getInput} />
                                            <p className="text text-center my-2">Drag and drop files here</p>
                                        </div>
                                    )}
                                </div>

                                {hasImages && (
                                    <div className="text-center">
                                        <div className="preview  my-3" style={{ 'display': 'flex', 'flexWrap': 'wrap', 'width': '100%' }}>
                                            {images}
                                        </div>

                                        <span className="btn btn-primary" {...props.getRoot} style={{ 'width': '200px' }}>
                                            <input {...props.getInput} />
                                            Replace Image
                                        </span>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
            </section>
        </>
    )
}

export default AddImageForm;