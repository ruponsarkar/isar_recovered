import React from "react";
import RightSide from "../components/common/rightSide";
import RefundPolicy from "../components/other/refundpolicy";
import { Paper } from "@mui/material";

const Representatives = () => {
  return (
    <>
      <div className="container mt-2">
        <div className="row">
          <div className="col-md-3 d-none d-lg-block">
            <RightSide />
          </div>
          <div className="col-md-8">
            <Paper>
              <div className="p-2" style={{height: "60vh"}}>
                <strong>
                  All publication applications and related procedures
                  originating from Turkey must be conducted through our
                  authorized representative: Ayaktan Gelen Sağlık Eğitim
                  Danışmanlık Hizmetleri Limited Company.
                </strong>
              </div>
            </Paper>
          </div>

          <div className="col-md-3 d-block d-sm-none">
            <RightSide />
          </div>
        </div>
      </div>
    </>
  );
};

export default Representatives;
