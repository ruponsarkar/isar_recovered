import React, { useState, useEffect } from "react";
import api from "../../API/api";
import { DataGrid } from "@mui/x-data-grid";
import Box from "@mui/material/Box";
import Button from "@mui/material/Button";
import Typography from "@mui/material/Typography";
import Modal from "@mui/material/Modal";

import { useParams } from "react-router-dom";
import { useNavigate } from "react-router-dom";
import AddVolumeForm from "../form/addVolumeForm";
import Card from "@mui/material/Card";
import LinearProgress from "@mui/material/LinearProgress";
import EditIcon from "@mui/icons-material/Edit";

const style = {
  position: "absolute",
  top: "50%",
  left: "50%",
  transform: "translate(-50%, -50%)",
  width: 400,
  bgcolor: "background.paper",
  border: "2px solid #000",
  boxShadow: 24,
  p: 4,
};

const AddIssue = () => {
  const navigate = useNavigate();
  const params = useParams();

  const [issue, setIssue] = useState();

  const [load, setLoad] = useState(false);

  const [open, setOpen] = React.useState(false);
  const handleOpen = () => setOpen(true);
  const handleClose = () => {
    setOpen(false);
    setIssueData({
      name: "",
      id: "",
    });
  };

  const [issueeData, setIssueData] = useState({
    name: "",
  });

  useEffect(() => {
    getIssue();
  }, []);

  const getIssue = () => {
    setLoad(true);
    api
      .getIssue(params.id)
      .then((res) => {
        setLoad(false);
        console.log(res);
        setIssue(res.data.issue);
      })
      .catch((err) => {
        console.log(err.response);
      });
  };

  const editIssue = (data) => {
    console.log("editIssue ", data);
    setOpen(true);
    setIssueData({
      id: data.id,
      name: data.name,
    });
  };

  const updateIssue = ()=>{
    api.updateIssue(issueeData)
    .then((res)=>{
        console.log("res ", res);
        getIssue();
        handleClose();
    })
    .catch((err)=>{
        console.log("err ", err);
    })
  }

  const columns = [
    { field: "name", headerName: "Title", width: 300 },
    {
      field: "id",
      headerName: "Action",
      width: 300,
      renderCell: (field) => (
        <>
          &nbsp;
          <button
            className="btn btn-success btn-sm"
            onClick={() => navigate(`/admin/Article/${field.row.id}`)}
          >
            + Add Article
          </button>
          <button
            className="btn btn-warning btn-sm mx-2"
            onClick={() => editIssue(field.row)}
          >
            <EditIcon />
          </button>
          &nbsp;
        </>
      ),
    },
  ];

  const handleAddInput = (e) => {
    setIssueData({
      ...issueeData,
      [e.target.name]: e.target.value,
    });
  };

  const saveIssue = () => {
    api
      .saveIssue(params.id, issueeData.name)
      .then((res) => {
        console.log(res);
        getIssue();
        handleClose();
      })
      .catch((err) => {
        console.log(err.response);
      });
  };

  const addEditor = () => {
    return (
      <>
        <div>
          <div className="m-2">
            <Button variant="contained" onClick={handleOpen}>
              + Add New Issue
            </Button>
          </div>

          <Modal
            open={open}
            onClose={handleClose}
            aria-labelledby="modal-modal-title"
            aria-describedby="modal-modal-description"
          >
            <Box sx={style}>
              <Typography id="modal-modal-title" variant="h6" component="h2">
                {/* Text in a modal */}
              </Typography>
              <Typography id="modal-modal-description" sx={{ mt: 2 }}>
                <AddVolumeForm
                  volumeData={issueeData}
                  handleAddInput={handleAddInput}
                />

                <div className="col-12 m-2 text-center">
                  {issueeData.id ? (
                    <button className="btn btn-success" onClick={updateIssue}>
                      Update 
                    </button>
                  ) : (
                    <button className="btn btn-success" onClick={saveIssue}>
                      Save
                    </button>
                  )}
                </div>
              </Typography>
            </Box>
          </Modal>
        </div>
      </>
    );
  };

  const volumeTable = () => {
    return (
      <>
        {issue && (
          <div style={{ height: 500, width: "100%" }}>
            <DataGrid
              rows={issue}
              columns={columns}
              getRowId={(row) => row.id}
              pageSize={5}
              rowsPerPageOptions={[8]}
              checkboxSelection
            />
          </div>
        )}
      </>
    );
  };

  return (
    <>
      {" "}
      {load && <LinearProgress />}
      <Card>
        <Card>
          <div className="m-2 text-center">
            <h3> All Issue</h3>
          </div>
        </Card>

        {addEditor()}

        {volumeTable()}
      </Card>
    </>
  );
};

export default AddIssue;
