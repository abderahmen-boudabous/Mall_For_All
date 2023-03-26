/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package utis;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.util.logging.Level;
import static java.util.logging.Level.SEVERE;
import java.util.logging.Logger;

/**
 *
 * @author user
 */
public class Maconnexion {
    
    //DB Credentials
     //const
    final String URL = "jdbc:mysql://localhost:3306/mall";
    final String USER = "root";
    final String PASSWORD = "";
    
    //var
    private Connection cnx;
    static Maconnexion instance;
   private Maconnexion(){
        try {
            cnx = DriverManager.getConnection(URL, USER, PASSWORD);
            System.out.println("Connexion établie avec succée");
        } catch (SQLException ex) {
            Logger.getLogger(Maconnexion.class.getName()).log(Level.SEVERE, null, ex);
        }
    }
     
    public Connection getCnx() {
        return cnx;
    }

    public static Maconnexion getInstance() {
        if(instance == null)
            instance = new Maconnexion();
        
        return instance;
    }
    
}
