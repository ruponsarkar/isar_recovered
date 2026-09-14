import "./App.css";
import { useEffect, useState } from "react";
import PublicInterface from "./interface/publicInterface";
import AdminInterface from "./interface/adminInterface";

function App() {
  const [user, setUser] = useState("");

  useEffect(() => {
    setUser(sessionStorage.getItem("user") ? "admin" : "public");
  }, [user]);

  return user === "admin" ? <AdminInterface /> : <PublicInterface />;
}

export default App;
