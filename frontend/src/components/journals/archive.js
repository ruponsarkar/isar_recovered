import React, { useEffect, useState } from "react";
import Card from "@mui/material/Card";
import { Button } from "@mui/material";
import Divider from "@mui/material/Divider";

import { useNavigate, Outlet } from "react-router-dom";

const Archive = (props) => {
  const navitate = useNavigate();

  const issues = () => {
    console.log(props.issues);
  };

  return (
    <>
      {props.volume.map((el) => (
        <Card>
          <div className="row pb-4 pt-1">
            <div className="col-md-12">
              <div className="text-dark left-line w-100 p-1 bg-light" disabled>
                {el.year}
              </div>
            </div>
            <Divider />

            {props.issues.map((issue) => (
              <>
                {el.name === issue.volume && (
                  <div className="col-md-12 p-1 mx-2">
                    <Button
                      variant="outlined"
                      // onClick={() => navitate(`/Articles/${issue.issue_id}`)}
                      onClick={(e) => {
                        if (e.ctrlKey || e.metaKey) {
                          window.open(`/Articles/${issue.issue_id}`, "_blank");
                        } else {
                          navitate(`/Articles/${issue.issue_id}`);
                        }
                      }}
                      className="m-1"
                      size="small"
                    >
                      {el.name}, {issue.issues}
                    </Button>
                  </div>
                )}
              </>
            ))}
          </div>

          <Divider color={"success"} />
        </Card>
      ))}
    </>
  );
};

export default Archive;
