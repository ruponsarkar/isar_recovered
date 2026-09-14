import React from "react";

const Table = () => {
    return (
        <>
            <section id="table" className="table">
                <div className="container">
                    <div className="row">
                        <div className="col-md-12">
                            <table>
                                <thead className="mx-4">
                                    <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Image</th>
                                    </tr>
                                </thead>

                                <tbody>
                            <tr>
                            <td>AAA</td>
                            <td>12/01/2023</td>
                            <td>Lorem ipsum Dolor sit amet.</td>
                            <td>file1</td>
                            </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </>
    )
}

export default Table;