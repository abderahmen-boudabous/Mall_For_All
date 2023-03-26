 /*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package moodels;

/**
 *
 * @author user
 */
public class User {
    private int iduser ; 
    private String email,nom,prenom,codepostale,ville,nom_boutique,image,numtel,reset_token,passwoed,adresse; 
    public String[] roles = new String[1];

    public User() {
    }

    public User(int iduser, String email, String nom, String prenom, String codepostale, String ville, String nom_boutique, String image, String numtel, String reset_token, String passwoed, String adresse, String[] roles) {
        this.iduser = iduser;
        this.email = email;
        this.nom = nom;
        this.prenom = prenom;
        this.codepostale = codepostale;
        this.ville = ville;
        this.nom_boutique = nom_boutique;
        this.image = image;
        this.numtel = numtel;
        this.reset_token = reset_token;
        this.passwoed = passwoed;
        this.adresse = adresse;
        this.roles = roles;
    }

    public User(String email, String nom, String prenom, String codepostale, String ville, String nom_boutique, String image, String numtel, String reset_token, String passwoed, String adresse, String[] roles) {
        this.email = email;
        this.nom = nom;
        this.prenom = prenom;
        this.codepostale = codepostale;
        this.ville = ville;
        this.nom_boutique = nom_boutique;
        this.image = image;
        this.numtel = numtel;
        this.reset_token = reset_token;
        this.passwoed = passwoed;
        this.adresse = adresse;
        this.roles = roles;
    }

    

   

  

   


    public int getIduser() {
        return iduser;
    }

    public void setIduser(int iduser) {
        this.iduser = iduser;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public String getPrenom() {
        return prenom;
    }

    public void setPrenom(String prenom) {
        this.prenom = prenom;
    }

    public String getCodepostale() {
        return codepostale;
    }

    public void setCodepostale(String codepostale) {
        this.codepostale = codepostale;
    }

    public String getVille() {
        return ville;
    }

    public void setVille(String ville) {
        this.ville = ville;
    }

    public String getNom_boutique() {
        return nom_boutique;
    }

    public void setNom_boutique(String nom_boutique) {
        this.nom_boutique = nom_boutique;
    }

    public String getImage() {
        return image;
    }

    public void setImage(String image) {
        this.image = image;
    }

    public String getNumtel() {
        return numtel;
    }

    public void setNumtel(String numtel) {
        this.numtel = numtel;
    }

   

    public String getReset_token() {
        return reset_token;
    }

    public void setReset_token(String reset_token) {
        this.reset_token = reset_token;
    }

   

    public String getPasswoed() {
        return passwoed;
    }

    public void setPasswoed(String passwoed) {
        this.passwoed = passwoed;
    }

    public String[] getRoles() {
        return roles;
    }

    public void setRoles(String[] roles) {
        this.roles = roles;
    }

    public String getAdresse() {
        return adresse;
    }

    public void setAdresse(String adresse) {
        this.adresse = adresse;
    }

    @Override
    public String toString() {
        return "User{" + "iduser=" + iduser + ", email=" + email + ", nom=" + nom + ", prenom=" + prenom + ", codepostale=" + codepostale + ", ville=" + ville + ", nom_boutique=" + nom_boutique + ", image=" + image + ", numtel=" + numtel + ", reset_token=" + reset_token + ", passwoed=" + passwoed + ", adresse=" + adresse + ", roles=" + roles[0] + '}';
    }

      public String getRolesAsString() {
        return String.join(",", roles);
    }
         public void setRolesFromString(String rolesString) {
        this.roles = rolesString.split(",");
    }
 
   
   
   
}
