import * as React from 'react';
import { useState, useEffect } from 'react';
import Table from '@mui/material/Table';
import TableBody from '@mui/material/TableBody';
import TableCell from '@mui/material/TableCell';
import TableContainer from '@mui/material/TableContainer';
import TableHead from '@mui/material/TableHead';
import TableRow from '@mui/material/TableRow';
import Paper from '@mui/material/Paper';

import MUIDataTable from "mui-datatables";
import Skeleton from 'react-loading-skeleton'
import 'react-loading-skeleton/dist/skeleton.css'


import api from "../../../API/api";

import Card from '@mui/material/Card';
import CardActions from '@mui/material/CardActions';
import CardContent from '@mui/material/CardContent';
import Button from '@mui/material/Button';
import Typography from '@mui/material/Typography';

import BlogPanel from './Blogs';

import Dialog from '@mui/material/Dialog';
import DialogActions from '@mui/material/DialogActions';
import DialogContent from '@mui/material/DialogContent';
import DialogTitle from '@mui/material/DialogTitle';
import Divider from '@mui/material/Divider';



const BlogTable = () => {
    

  const [blogs, setBlogs] = useState([]);
  const [loading, setLoading] = useState(true);

  const [modalopen, setModalopen] = useState([false]);
  const handleOpenModal = () => setModalopen(true);

  const [open, setOpen] = React.useState(false);

    const handleClickOpen = () => {
        setOpen(true);
    };

    const handleClose = () => {
        setOpen(false);
    };

    useEffect(() => {
      getBlog();

  }, []);


  const getBlog = () => {
      api.getBlog()
          .then((res) => {
              console.log("response",res);
              setBlogs(res.data.allblogs);
              setLoading(false);
          })
          .catch((err) => {
              console.log(err);
          })
  }


  const columns = [
      {
          name: "title",
          label: "Title",
          options: {
              filter: true,
              sort: true,
          }
      },
      {
          name: "hashtag",
          label: "Hashtag",
          options: {
              filter: true,
              sort: true,
          }
      },
      {
          name: "category",
          label: "Category",
          options: {
              filter: true,
              sort: false,
          }
      },
      {
          name: "author",
          label: "Author",
          options: {
              filter: true,
              sort: false,
          }
      },
      {
          name: "details",
          label: "Details",
          options: {
              filter: false,
              sort: false,
              display: false
          }
      },
  ];
  
  const options = {
    rowsPerPage: 10,
    filterType: '',
    selectableRows: false,
    responsive: 'standard',

    expandableRows: true,
    expandableRowsHeader: false,
    expandableRowsOnClick: true,

    renderExpandableRow: (rowData, rowMeta) => {
        const colSpan = rowData.length + 1;

        return (
            <>
                <TableRow>
                    <TableCell colSpan={colSpan}>
                        <Card>
                            {/* <CardMedia
                                sx={{ height: 240 }}

                                image="/assets/portfolio/portfolio-1.jpg"
                                title="green iguana"
                            /> */}
                            <CardContent>
                                <Typography gutterBottom variant="h5" component="div">
                                    {rowData[0]} :
                                </Typography>
                                <Typography variant="body2" color="text.secondary">
                                    <div className='my-3 text-center'>

                                    <div dangerouslySetInnerHTML={{__html: rowData[4]}} />

                                    </div>
                                </Typography>
                            </CardContent>
                            <CardActions>

                                <div className="col-12 text-center">
                                    <Divider style={{ width: '100%', margin: '5px' }} />
                                    <span className="p-2">
                                        <Button color="success" size="small" variant="outlined" onClick={handleClickOpen}>
                                            Update
                                        </Button>
                                    </span>

                                    <span className="p-2">
                                        <Button color="error" size="small" variant="outlined" onClick={handleClickOpen}>
                                            Delete
                                        </Button>
                                    </span>
                                </div>

                            </CardActions>
                        </Card>

                        <Dialog
                            fullWidth
                            maxWidth="lg"
                            open={open} onClose={handleClose}>
                            <DialogTitle> <span className="dotted"> Update</span></DialogTitle>
                            <DialogContent>
                                <BlogPanel />
                            </DialogContent>
                            <DialogActions>
                                <Button onClick={handleClose}>Cancel</Button>
                                {/* <Button onClick={handleClose}>Subscribe</Button> */}
                            </DialogActions>
                        </Dialog>


                    </TableCell>
                </TableRow>
            </>
        );
    },
    onRowExpansionChange: (curExpanded, allExpanded, rowsExpanded) =>
        console.log(curExpanded, allExpanded, rowsExpanded),

};

const skeltonloading = () => {
  return (
      <>
          <div className="border p-4">
              <div className="pb-2">
                  <Skeleton inline={true} width={150} height={30} />
              </div>

              <div className="pb-2">
                  <Skeleton height={30} />
              </div>
              <div>
                  <Skeleton height={25} count={10} />
              </div>

          </div>
      </>
  )

}
   
return (
  <>
      <div className="row py-4">
          {loading ? skeltonloading() :
              <MUIDataTable
                  title={"Blog Details"}
                  data={blogs}
                  columns={columns}
                  options={options}
              />}
      </div>
  </>
);

}

export default BlogTable;
