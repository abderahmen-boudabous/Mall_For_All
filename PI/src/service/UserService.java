/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package service;

import interfaces.InterfaceCRUD;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;
import moodels.User;
import utis.Maconnexion;

/**
 *
 * @author user
 */
public class UserService implements InterfaceCRUD<User> {
    Connection conn = Maconnexion.getInstance().getCnx();

    @Override
    public void insert(User t) {
        try {
            String requete="insert into user  (email,roles,password,nom,prenom,adresse,codepostale,ville,nom_boutique,image,numtel,rest_token) values (?,?,?,?,?,?,?,?,?,?,?,?) ";
            
            PreparedStatement usr=conn.prepareStatement(requete);
            usr.setString(1, t.getEmail());
            usr.setString(2, t.getRolesAsString());
            usr.setString(3, t.getPasswoed());
            usr.setString(4, t.getNom());
            usr.setString(5, t.getPrenom());
            usr.setString(6, t.getAdresse());
            usr.setString(7, t.getCodepostale());
            usr.setString(7, t.getVille());
            usr.setString(7, t.getNom_boutique());
            usr.setString(7, t.getImage());
            usr.setString(7, t.getNumtel());
            usr.setString(7, t.getReset_token());
            //usr.setInt(8, t.getId_role().getId_role());
            usr.executeUpdate();
        } catch (SQLException ex) {
            Logger.getLogger(UserService.class.getName()).log(Level.SEVERE, null, ex);
        }
        } 

    @Override
    public void delete(int id) {
        try {
            String req = "Delete FROM user WHERE id ='"+id+"';" ;
            
            Statement st = conn.createStatement();
            st.executeUpdate(req);
            System.out.println("user  supprimée avec succés");
        } catch (SQLException ex) {
            Logger.getLogger(UserService.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    @Override
    public void update(User t) {
        try {
           String req="UPDATE commande SET  email`= ? ,`roles`= ? ,`password`= ?,`nom`= ?,`prenom`= ?,`adresse`= ?,`codepostale`= ?,`ville`= ?,`nom_boutique`= ?,`image`= ?,`numtel`= ?,`rest_token`= ?WHERE id= ?";
            PreparedStatement usr=conn.prepareStatement(req);
            usr.setString(1, t.getEmail());
            usr.setString(2, t.getRolesAsString());
            usr.setString(3, t.getPasswoed());
            usr.setString(4, t.getNom());
            usr.setString(5, t.getPrenom());
            usr.setString(6, t.getAdresse());
            usr.setString(7, t.getCodepostale());
            usr.setString(8, t.getVille());
            usr.setString(9, t.getNom_boutique());
            usr.setString(10, t.getImage());
            usr.setString(11, t.getNumtel());
            usr.setString(12, t.getReset_token());
             usr.setInt(13, t.getIduser());
             System.out.println("user a ete modifiee avec succes ");
        } catch (SQLException ex) {
            Logger.getLogger(UserService.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    @Override
    public ArrayList<User> readAll() {
        ArrayList<User> users = new ArrayList<>();
        try {
            
            String req = "SELECT * FROM user";
            Statement st = conn.createStatement();
            ResultSet rs = st.executeQuery(req);
            while (rs.next()) {                
                User u = new User();
                u.setIduser(rs.getInt(1));
                u.setEmail(rs.getString(2));
                u.setRolesFromString(rs.getString(3));
                u.setPasswoed(rs.getString(4));
                u.setNom(rs.getString(5));
                u.setPrenom(rs.getString(6));
                u.setAdresse(rs.getString(7));
                u.setCodepostale(rs.getString(8));
                u.setVille(rs.getString(9));
                u.setNom_boutique(rs.getString(10));    
                u.setImage(rs.getString(11));
                u.setNumtel(rs.getString(12));
                u.setReset_token(rs.getString(13));
                     
                
                
               
                users.add(u);
            }
        } catch (SQLException ex) {
            ex.printStackTrace();
        }
        return users;
    }
    
    public String extractRole(String role) {
    return role.substring(2, role.length() - 2);
}

    @Override
    public User readById(int id) {
        User u = new User();
        String[] role = new String[1] ;
        try {
            
       String req="SELECT * FROM user WHERE `id`='"+id+"'";
            Statement st = conn.createStatement();
            ResultSet rs = st.executeQuery(req);
            rs.beforeFirst();
            rs.next();
                u.setIduser(rs.getInt(1));
                u.setEmail(rs.getString(2));
                role[0] = extractRole(rs.getString(3));
                u.setRoles(role);
                u.setPasswoed(rs.getString(4));
                u.setNom(rs.getString(5));
                u.setPrenom(rs.getString(6));
                u.setAdresse(rs.getString(7));
                u.setCodepostale(rs.getString(8));
                u.setVille(rs.getString(9));
                u.setNom_boutique(rs.getString(10));
                u.setImage(rs.getString(11));
                u.setNumtel(rs.getString(12));
                u.setReset_token(rs.getString(13));
                     
        } catch (SQLException ex) {
            ex.printStackTrace();
        }
        return  u;
    }

    @Override
    public ArrayList<User> sortBy(String nom_column, String Asc_Dsc) {
       List<User> ListeUserTriee=new ArrayList<>();
        try {
              String req="SELECT * FROM user ORDER BY "+nom_column+" "+Asc_Dsc ;
              Statement ste = conn.createStatement();
              ResultSet rs=ste.executeQuery(req);
              while(rs.next()){
                  User u=new  User();
                u.setIduser(rs.getInt(1));
                u.setEmail(rs.getString(2));
                u.setRolesFromString(rs.getString(3));
                u.setPasswoed(rs.getString(4));
                u.setNom(rs.getString(5));
                u.setPrenom(rs.getString(6));
                u.setAdresse(rs.getString(7));
                u.setCodepostale(rs.getString(8));
                u.setVille(rs.getString(9));
                u.setNom_boutique(rs.getString(10));
                u.setImage(rs.getString(11));
                u.setNumtel(rs.getString(12));
                u.setReset_token(rs.getString(13));
                     

                  ListeUserTriee.add(u);
              }  
          } catch (SQLException ex) {
         ex.printStackTrace();         
          }
           return (ArrayList<User>) ListeUserTriee ;    }

    }
        
        
           


    
    
    
    
    
    
    

